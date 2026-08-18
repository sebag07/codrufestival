import { readFile, writeFile } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const themeRoot = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const artistsPath = path.join(themeRoot, 'data', 'artists.json');

const saturdayArtistIds = new Set([
  'subcarpati',
  'puya',
  'e-an-na',
  'balkandreea-and-the-balkan-sisters',
  'dj-ma-ta',
  'paulvlj',
  'albert-nbn',
  'ian',
  'mgk',
  'noua-unspe',
  'el-cartel-reggaeton-afterparty',
  '4awl',
  'amalia-gaita',
  'asaway',
  'bob-ramanka',
  'exob',
  'kayf',
  'leanu',
  'mandu',
  'mini-zuchini',
  'narciss',
  'raul-prodan',
  'rave-with-your-kids-donisan-sabincp',
  'tano',
  'tulvan',
  'blutrina',
  'dordeduh',
  'emo-reunion',
  'nocturn',
]);

const newArtists = [
  {
    id: 'amalia-gaita',
    name: 'Amalia Gaiță',
    level: 'level5',
    days: ['saturday'],
    spotify_id: '',
    spotify_url: '',
    spotify_embed_url: '',
    image: '',
    image_override: '',
    genres: [],
    followers: null,
    popularity: null,
    description:
      'Amalia Gaiță este parte din lineup-ul Codru Festival. Detalii despre programul live vor fi anunțate.',
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
    id: 'rave-with-your-kids-donisan-sabincp',
    name: 'Rave With Your Kid[s] – Donisan / SabinCP',
    level: 'level5',
    days: ['saturday'],
    spotify_id: '',
    spotify_url: '',
    spotify_embed_url: '',
    image: '',
    image_override: '',
    genres: [],
    followers: null,
    popularity: null,
    description:
      'Rave With Your Kid[s] este un concept de petrecere care aduce Donisan și SabinCP pe scenă, într-un set dedicat energiei de club și publicului divers.',
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

function withSaturdayDay(artist) {
  if (!saturdayArtistIds.has(artist.id)) {
    return artist;
  }

  const days = normalizeDays(artist.days);
  if (!days.includes('saturday')) {
    days.push('saturday');
  }

  return {
    ...artist,
    days,
  };
}

const source = JSON.parse(await readFile(artistsPath, 'utf8'));
const artistsById = new Map((source.artists ?? []).map((artist) => [artist.id, withSaturdayDay(artist)]));

for (const artist of newArtists) {
  if (!artistsById.has(artist.id)) {
    artistsById.set(artist.id, artist);
  } else {
    artistsById.set(artist.id, withSaturdayDay(artistsById.get(artist.id)));
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

const saturdayCount = orderedArtists.filter((artist) => normalizeDays(artist.days).includes('saturday')).length;
console.log(`Tagged ${saturdayCount} artists for Saturday in ${path.relative(process.cwd(), artistsPath)}`);
