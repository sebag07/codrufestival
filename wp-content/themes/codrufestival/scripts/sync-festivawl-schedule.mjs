import { readFile, writeFile } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const themeRoot = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const artistsPath = path.join(themeRoot, 'data', 'artists.json');

const DEFAULT_FESTIVAL_ID = '569';
const API_BASE_URL = 'https://api.festivawl.com/app';

const DAY_ORDER = ['friday', 'saturday', 'sunday'];
const STAGE_PRIORITY = ['main', 'air', 'water', 'earth', 'fire', 'art-bus'];

const FESTIVAL_DAY_DATES = {
  '2026-08-28': 'friday',
  '2026-08-29': 'saturday',
  '2026-08-30': 'sunday',
};

const STAGE_MAP = {
  MAINSTAGE: 'main',
  'QUINTESSENCE MAIN': 'main',
  'AIR STAGE': 'air',
  'WATER STAGE': 'water',
  'EARTH STAGE': 'earth',
  'FIRE STAGE': 'fire',
  'CODRU ART BUS ART STAGE': 'art-bus',
};

const API_NAME_ALIASES = {
  's a r s': 'sars',
  'gipsy kings featuring tonino balardo': 'gipsy-kings',
  'adelinmm': 'adelin-mm',
  'disko anksiyete': 'disko-anksyete',
  'petre stefan': 'petre-stefan',
  'claudia serdan and jimi el laco': 'claudia-serdan-and-jimi-el-laco',
  'balkandreea and the balkan sisters': 'balkandreea-and-the-balkan-sisters',
  'damian and brothers': 'damian-and-brothers',
  'robin and the backstabbers': 'robin-and-the-backstabbers',
  'surorile osoianu si lupii lui calancea': 'surorile-osoianu-si-lupii-lui-calancea',
  'lupii lui calancea and surorile osoianu': 'surorile-osoianu-si-lupii-lui-calancea',
  'noua unspe': 'noua-unspe',
  'subcarpati': 'subcarpati',
  'alternosfera': 'alternosfera',
  'mini zuchini': 'mini-zucchini',
  'mini zucchini': 'mini-zucchini',
  'el cartel reggaeton afterparty': 'el-cartel-reggaeton-afterparty',
  'adrian despot and cezar popescu': 'adrian-despot-and-cezar-popescu',
  'noid b2b oigan': 'noid-b2b-oigan',
  'no id b2b oigan': 'noid-b2b-oigan',
  'dj ma ta': 'dj-ma-ta',
  'sar casm': 'sar-casm',
  'paulvlj': 'paulvlj',
  'rave with your kids donisan and sabincp': 'rave-with-your-kids-donisan-sabincp',
  'rave with your kid s donisan and sabincp': 'rave-with-your-kids-donisan-sabincp',
  'e an na': 'e-an-na',
  '4awl': '4awl',
  'mgk666': 'mgk',
  'mgk': 'mgk',
  'idk': 'idk',
  'zo': 'zo',
  'ufo': 'ufo',
  'oigan': 'oigan',
  'mandu': 'mandu',
  'calinacho': 'calinacho',
  'lucawts': 'lucawts',
  'oscar': 'oscar',
  'ternipe': 'ternipe',
  'puya': 'puya',
  'hvnds': 'hvnds',
  'blutrina': 'blutrina',
  'dordeduh': 'dordeduh',
  'doomnezeu': 'doomnezeu',
  'ordinul negru': 'ordinul-negru',
  'sear bliss': 'sear-bliss',
  'sur austru': 'sur-austru',
  'emo reunion': 'emo-reunion',
  'nocturn': 'nocturn',
  'damage ctrl': 'damage-ctrl',
  'acoustic boyz': 'acoustic-boyz',
  'bob ramanka': 'bob-ramanka',
  'raul prodan': 'raul-prodan',
  'soulmaze': 'soulmaze',
  'zoleevee': 'zoleevee',
  'leanu': 'leanu',
  'narciss': 'narciss',
  'tulvan': 'tulvan',
  'exob': 'exob',
  'kayf': 'kayf',
  'tano': 'tano',
  'yoshuu': 'yoshuu',
  'ciresan': 'ciresan',
  'cypher': 'cypher',
  'drew': 'drew',
  'emu': 'emu',
  'razy': 'razy',
  'revan': 'revan',
  'asaway': 'asaway',
  'bia and mc geeza': 'bia',
  'vanilla': 'vanilla',
  'rava': 'rava',
  'albert nbn': 'albert-nbn',
  'ian': 'ian',
  'prapad': 'prapad',
  she: 'she',
  'amalia gaita': 'amalia-gaita',
  'zabranena muzika': 'zabranena-muzika',
  beatheice: 'beatheice',
};

const COMPOSITE_EVENT_PATTERNS = [
  /\btakeover\b/i,
  /\bspecial guest\b/i,
  /\bwarm-up\b/i,
  /\bb2b\b/i,
  /\band\b.+\band\b/i,
];

function stripHtml(value) {
  return String(value ?? '')
    .replace(/<[^>]+>/g, ' ')
    .replace(/\s+/g, ' ')
    .trim();
}

function normalizeForMatch(value) {
  return stripHtml(value)
    .normalize('NFD')
    .replace(/\p{M}/gu, '')
    .toLowerCase()
    .replace(/&/g, ' and ')
    .replace(/[^a-z0-9]+/g, ' ')
    .trim()
    .replace(/\s+/g, ' ');
}

function formatSchedule(startTime, endTime) {
  const start = startTime?.slice(11, 16) ?? '';
  const end = endTime?.slice(11, 16) ?? '';

  if (!start || !end) {
    return '';
  }

  return `${start} - ${end}`;
}

function mapStage(sceneName) {
  const normalized = String(sceneName ?? '').trim().toUpperCase();
  return STAGE_MAP[normalized] ?? normalized.toLowerCase().replace(/\s+/g, '-');
}

function mapDayFromStartTime(startTime, festivalStartDate) {
  const date = String(startTime ?? '').slice(0, 10);

  if (FESTIVAL_DAY_DATES[date]) {
    return FESTIVAL_DAY_DATES[date];
  }

  if (festivalStartDate && date < festivalStartDate) {
    return null;
  }

  return null;
}

function buildArtistLookup(artists) {
  const lookup = new Map();

  for (const artist of artists) {
    const candidates = [
      normalizeForMatch(artist.id.replace(/-/g, ' ')),
      normalizeForMatch(stripHtml(artist.name)),
      normalizeForMatch(artist.spotify_name),
    ].filter(Boolean);

    for (const candidate of candidates) {
      if (!lookup.has(candidate)) {
        lookup.set(candidate, artist.id);
      }
    }
  }

  return lookup;
}

function isCompositeEvent(eventName) {
  return COMPOSITE_EVENT_PATTERNS.some((pattern) => pattern.test(eventName));
}

function resolveArtistId(eventName, lookup, { allowComposite = false } = {}) {
  const normalized = normalizeForMatch(eventName);

  if (!normalized) {
    return null;
  }

  if (!allowComposite && isCompositeEvent(eventName)) {
    return null;
  }

  if (API_NAME_ALIASES[normalized]) {
    return API_NAME_ALIASES[normalized];
  }

  if (lookup.has(normalized)) {
    return lookup.get(normalized);
  }

  const fuzzyMatches = [];

  for (const [candidate, artistId] of lookup.entries()) {
    if (normalized === candidate || normalized.includes(candidate) || candidate.includes(normalized)) {
      fuzzyMatches.push(artistId);
    }
  }

  const uniqueMatches = [...new Set(fuzzyMatches)];

  if (uniqueMatches.length === 1) {
    return uniqueMatches[0];
  }

  return null;
}

function extractArtistIdsFromCompositeEvent(eventName, lookup) {
  const workingName = String(eventName)
    .replace(/^rup takeover\s*-\s*/i, '')
    .replace(/\bspecial guest\b.*$/i, '')
    .replace(/\([^)]*\)\s*$/i, '')
    .trim();

  const segments = workingName
    .split(/\s+b2b\s+|\s+\+\s+|\s*&\s*/i)
    .map((segment) => segment.trim())
    .filter(Boolean);

  const artistIds = [];

  for (const segment of segments) {
    const artistId = resolveArtistId(segment, lookup, { allowComposite: true });

    if (artistId && !artistIds.includes(artistId)) {
      artistIds.push(artistId);
    }
  }

  return artistIds;
}

function resolveArtistIdsForEvent(eventName, lookup) {
  const directMatch = resolveArtistId(eventName, lookup);

  if (directMatch) {
    return [directMatch];
  }

  if (isCompositeEvent(eventName)) {
    return extractArtistIdsFromCompositeEvent(eventName, lookup);
  }

  return [];
}

function sortDays(days) {
  return [...days].sort((left, right) => DAY_ORDER.indexOf(left) - DAY_ORDER.indexOf(right));
}

function choosePrimaryStage(stages) {
  for (const stage of STAGE_PRIORITY) {
    if (stages.includes(stage)) {
      return stage;
    }
  }

  return stages[0] ?? '';
}

function choosePrimaryPerformance(performances) {
  return [...performances].sort((left, right) => {
    const stageDiff =
      STAGE_PRIORITY.indexOf(left.stage) - STAGE_PRIORITY.indexOf(right.stage);
    if (stageDiff !== 0) {
      return stageDiff;
    }

    const dayDiff = DAY_ORDER.indexOf(left.day) - DAY_ORDER.indexOf(right.day);
    if (dayDiff !== 0) {
      return dayDiff;
    }

    return left.startTime.localeCompare(right.startTime);
  })[0];
}

function buildArtistScheduleData(performances) {
  const uniquePerformances = [];
  const seen = new Set();

  for (const performance of performances) {
    const key = `${performance.day}|${performance.stage}|${performance.schedule}|${performance.startTime}`;
    if (seen.has(key)) {
      continue;
    }

    seen.add(key);
    uniquePerformances.push(performance);
  }

  const days = sortDays([...new Set(uniquePerformances.map((entry) => entry.day))]);
  const stages = [...new Set(uniquePerformances.map((entry) => entry.stage))].sort(
    (left, right) => STAGE_PRIORITY.indexOf(left) - STAGE_PRIORITY.indexOf(right),
  );

  const scheduleByDay = {};
  const stageByDay = {};

  for (const day of days) {
    const dayPerformances = uniquePerformances.filter((entry) => entry.day === day);
    const primaryForDay = choosePrimaryPerformance(dayPerformances);

    scheduleByDay[day] = primaryForDay.schedule;
    stageByDay[day] = primaryForDay.stage;
  }

  const primary = choosePrimaryPerformance(uniquePerformances);
  const singlePerformance = uniquePerformances.length === 1;

  return {
    days,
    stages,
    stage: choosePrimaryStage(stages),
    schedule: singlePerformance ? primary.schedule : '',
    schedule_by_day: scheduleByDay,
    stage_by_day: stageByDay,
  };
}

async function fetchJson(url) {
  const response = await fetch(url, {
    headers: {
      Accept: 'application/json',
      'User-Agent': 'Codru Festival Schedule Sync',
    },
  });

  if (!response.ok) {
    throw new Error(`Request failed (${response.status}) for ${url}`);
  }

  return response.json();
}

function parseArgs(argv) {
  const args = {
    dryRun: false,
    festivalId: process.env.FESTIVAWL_FESTIVAL_ID || DEFAULT_FESTIVAL_ID,
  };

  for (const arg of argv) {
    if (arg === '--dry-run') {
      args.dryRun = true;
      continue;
    }

    if (arg.startsWith('--festival-id=')) {
      args.festivalId = arg.split('=')[1];
    }
  }

  return args;
}

const args = parseArgs(process.argv.slice(2));
const performanceUrl = `${API_BASE_URL}/performance/${args.festivalId}`;
const festivalUrl = `${API_BASE_URL}/festival/${args.festivalId}`;

const [performanceData, festivalData, source] = await Promise.all([
  fetchJson(performanceUrl),
  fetchJson(festivalUrl),
  readFile(artistsPath, 'utf8').then((contents) => JSON.parse(contents)),
]);

const artists = source.artists ?? [];
const lookup = buildArtistLookup(artists);
const performancesByArtist = new Map();
const unmatchedEvents = [];
const skippedPreFestivalEvents = [];

for (const [dayIndex, dayEvents] of Object.entries(performanceData.completeEventSchedule ?? {})) {
  for (const event of dayEvents) {
    const eventObject = event.event ?? {};
    const eventName =
      typeof eventObject === 'object' && eventObject !== null
        ? eventObject.eventName
        : String(eventObject);
    const day = mapDayFromStartTime(event.startTime, festivalData.startDate);

    if (!day) {
      skippedPreFestivalEvents.push({
        dayIndex,
        eventName,
        startTime: event.startTime,
      });
      continue;
    }

    const artistIds = resolveArtistIdsForEvent(eventName, lookup);

    if (!artistIds.length) {
      unmatchedEvents.push({
        dayIndex,
        eventName,
        startTime: event.startTime,
        stage: event.stage?.sceneName ?? '',
      });
      continue;
    }

    const performance = {
      day,
      dayIndex: Number(dayIndex),
      stage: mapStage(event.stage?.sceneName),
      schedule: formatSchedule(event.startTime, event.endTime),
      startTime: event.startTime,
      endTime: event.endTime,
      eventName,
    };

    for (const artistId of artistIds) {
      if (!performancesByArtist.has(artistId)) {
        performancesByArtist.set(artistId, []);
      }

      performancesByArtist.get(artistId).push(performance);
    }
  }
}

const updatedArtists = artists.map((artist) => {
  const performances = performancesByArtist.get(artist.id);

  if (!performances?.length) {
    return artist;
  }

  const scheduleData = buildArtistScheduleData(performances);

  return {
    ...artist,
    days: scheduleData.days,
    stages: scheduleData.stages,
    stage: scheduleData.stage,
    schedule: scheduleData.schedule,
    schedule_by_day: scheduleData.schedule_by_day,
    stage_by_day: scheduleData.stage_by_day,
  };
});

const matchedArtistIds = [...performancesByArtist.keys()];
const unmatchedLocalArtists = artists
  .map((artist) => artist.id)
  .filter((artistId) => !matchedArtistIds.includes(artistId));

const output = {
  ...source,
  updated_at: new Date().toISOString(),
  festivawl_schedule_sync: {
    festival_id: Number(args.festivalId),
    festival_name: festivalData.name ?? '',
    synced_at: new Date().toISOString(),
    matched_artists: matchedArtistIds.length,
    unmatched_events: unmatchedEvents.length,
    skipped_pre_festival_events: skippedPreFestivalEvents.length,
    unmatched_local_artists: unmatchedLocalArtists.length,
  },
  artists: updatedArtists,
};

if (!args.dryRun) {
  await writeFile(artistsPath, `${JSON.stringify(output, null, 2)}\n`);
}

console.log(`Festivawl schedule sync for ${festivalData.name} (ID ${args.festivalId})`);
console.log(`Matched artists: ${matchedArtistIds.length}/${artists.length}`);
console.log(`Unmatched API events: ${unmatchedEvents.length}`);
console.log(`Skipped pre-festival events: ${skippedPreFestivalEvents.length}`);

if (skippedPreFestivalEvents.length) {
  console.log('\nSkipped pre-festival events:');
  for (const entry of skippedPreFestivalEvents) {
    console.log(`  - ${entry.eventName} (${entry.startTime})`);
  }
}

if (unmatchedEvents.length) {
  console.log('\nUnmatched API events:');
  for (const entry of unmatchedEvents) {
    console.log(`  - ${entry.eventName} @ ${entry.stage} (${entry.startTime})`);
  }
}

if (unmatchedLocalArtists.length) {
  console.log('\nLocal artists without API performances:');
  for (const artistId of unmatchedLocalArtists) {
    console.log(`  - ${artistId}`);
  }
}

if (args.dryRun) {
  console.log('\nDry run only. No files were written.');
} else {
  console.log(`\nUpdated ${path.relative(process.cwd(), artistsPath)}`);
}
