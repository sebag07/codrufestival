import { readFile, writeFile } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const themeRoot = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const artistsPath = path.join(themeRoot, 'data', 'artists.json');

const stageAssignments = {
  main: [
    'gipsy-kings',
    'sars',
    'ternipe',
    'alternosfera',
    'subcarpati',
    'puya',
    'surorile-osoianu-si-lupii-lui-calancea',
    'damian-and-brothers',
    'e-an-na',
    'hvnds',
    'robin-and-the-backstabbers',
    'dj-ma-ta',
    'balkandreea-and-the-balkan-sisters',
  ],
  air: [
    'adelin-mm',
    'albert-nbn',
    'calinacho',
    'ian',
    'idk',
    'lucawts',
    'mgk',
    'noua-unspe',
    'oscar',
    'petre-stefan',
    'rava',
    'vanilla',
    'el-cartel-reggaeton-afterparty',
  ],
  water: [
    '4awl',
    'acoustic-boyz',
    'adrian-despot-and-cezar-popescu',
    'amalia-gaita',
    'asaway',
    'bia',
    'bob-ramanka',
    'ciresan',
    'claudia-serdan-and-jimi-el-laco',
    'cypher',
    'damage-ctrl',
    'drew',
    'emu',
    'exob',
    'kayf',
    'leanu',
    'mini-zucchini',
    'narciss',
    'noid-b2b-oigan',
    'oigan',
    'paulvlj',
    'raul-prodan',
    'razy',
    'revan',
    'sar-casm',
    'soulmaze',
    'tano',
    'tulvan',
    'ufo',
    'yoshuu',
    'zoleevee',
    'beatheice',
    'rave-with-your-kids-donisan-sabincp',
    'zabranena-muzika',
  ],
  earth: ['mandu'],
  fire: [
    'blutrina',
    'prapad',
    'dordeduh',
    'doomnezeu',
    'sur-austru',
    'she',
    'sear-bliss',
    'ordinul-negru',
    'nocturn',
    'disko-anksyete',
    'emo-reunion',
  ],
};

const stageByArtistId = new Map();

for (const [stage, artistIds] of Object.entries(stageAssignments)) {
  for (const artistId of artistIds) {
    if (stageByArtistId.has(artistId)) {
      throw new Error(`Artist "${artistId}" is assigned to multiple stages.`);
    }

    stageByArtistId.set(artistId, stage);
  }
}

const source = JSON.parse(await readFile(artistsPath, 'utf8'));
const artists = (source.artists ?? []).map((artist) => {
  const stage = stageByArtistId.get(artist.id);

  if (!stage) {
    const { stage: _removed, ...rest } = artist;
    return rest;
  }

  return {
    ...artist,
    stage,
  };
});

const unknownIds = [...stageByArtistId.keys()].filter(
  (artistId) => !artists.some((artist) => artist.id === artistId),
);

if (unknownIds.length) {
  throw new Error(`Unknown artist ids in stage assignments: ${unknownIds.join(', ')}`);
}

await writeFile(
  artistsPath,
  `${JSON.stringify(
    {
      ...source,
      updated_at: new Date().toISOString(),
      artists,
    },
    null,
    2,
  )}\n`,
);

const counts = Object.fromEntries(
  Object.keys(stageAssignments).map((stage) => [
    stage,
    artists.filter((artist) => artist.stage === stage).length,
  ]),
);

console.log(`Applied stage assignments in ${path.relative(process.cwd(), artistsPath)}`);
console.log(counts);
console.log(`Unassigned artists: ${artists.filter((artist) => !artist.stage).length}`);
