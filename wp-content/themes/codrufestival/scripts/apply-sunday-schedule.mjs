import { readFile, writeFile } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const themeRoot = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const artistsPath = path.join(themeRoot, 'data', 'artists.json');

const sundayArtistIds = new Set([
  'gipsy-kings',
  'ternipe',
  'surorile-osoianu-si-lupii-lui-calancea',
  'hvnds',
  'dj-ma-ta',
  'zabranena-muzika',
  'adelin-mm',
  'petre-stefan',
  'rava',
  'vanilla',
  'el-cartel-reggaeton-afterparty',
  'acoustic-boyz',
  'beatheice',
  'bia',
  'ciresan',
  'mandu',
  'rave-with-your-kids-donisan-sabincp',
  'soulmaze',
  'ufo',
  'zoleevee',
  'doomnezeu',
  'ordinul-negru',
  'prapad',
]);

const newArtists = [
  {
    id: 'zabranena-muzika',
    name: 'Zabranena Muzika <br> <small>GORO + BAT SIMO</small>',
    level: 'level5',
    days: ['sunday'],
    spotify_id: '',
    spotify_url: '',
    spotify_embed_url: '',
    image: '',
    image_override: '',
    genres: [],
    followers: null,
    popularity: null,
    description:
      'Zabranena Muzika este proiectul lui Goro și Bat Simo, parte din RUP Takeover alături de DJ MĂ-TA în programul de duminică al Codru Festival.',
    socials: {
      facebook: '',
      youtube: '',
      tiktok: '',
      twitter: '',
      instagram: '',
      spotify: '',
    },
  },
  {
    id: 'beatheice',
    name: 'Beatheice',
    level: 'level5',
    days: ['sunday'],
    spotify_id: '',
    spotify_url: '',
    spotify_embed_url: '',
    image: '',
    image_override: '',
    genres: [],
    followers: null,
    popularity: null,
    description: 'Beatheice este parte din lineup-ul Codru Festival. Detalii despre programul live vor fi anunțate.',
    socials: {
      facebook: '',
      youtube: '',
      tiktok: '',
      twitter: '',
      instagram: '',
      spotify: '',
    },
  },
  {
    id: 'prapad',
    name: 'Prăpăd',
    level: 'level6',
    days: ['sunday'],
    spotify_id: '',
    spotify_url: '',
    spotify_embed_url: '',
    image: '',
    image_override: '',
    genres: [],
    followers: null,
    popularity: null,
    description: 'Prăpăd este parte din lineup-ul Codru Festival. Detalii despre proiect vor fi anunțate.',
    socials: {
      facebook: '',
      youtube: '',
      tiktok: '',
      twitter: '',
      instagram: '',
      spotify: '',
    },
  },
];

function normalizeDays(days) {
  if (!Array.isArray(days)) {
    return [];
  }

  return [...new Set(days.map((day) => String(day).trim().toLowerCase()).filter(Boolean))];
}

function withSundayDay(artist) {
  if (!sundayArtistIds.has(artist.id)) {
    return artist;
  }

  const days = normalizeDays(artist.days);
  if (!days.includes('sunday')) {
    days.push('sunday');
  }

  return {
    ...artist,
    days,
  };
}

const source = JSON.parse(await readFile(artistsPath, 'utf8'));
const artistsById = new Map((source.artists ?? []).map((artist) => [artist.id, withSundayDay(artist)]));

for (const artist of newArtists) {
  if (!artistsById.has(artist.id)) {
    artistsById.set(artist.id, artist);
  } else {
    artistsById.set(artist.id, withSundayDay(artistsById.get(artist.id)));
  }
}

const orderedArtists = [...artistsById.values()];

await writeFile(
  artistsPath,
  `${JSON.stringify(
    {
      ...source,
      updated_at: new Date().toISOString(),
      artists: orderedArtists,
    },
    null,
    2,
  )}\n`,
);

const sundayCount = orderedArtists.filter((artist) => normalizeDays(artist.days).includes('sunday')).length;
console.log(`Tagged ${sundayCount} artists for Sunday in ${path.relative(process.cwd(), artistsPath)}`);
