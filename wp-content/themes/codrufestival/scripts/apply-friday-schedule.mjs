import { readFile, writeFile } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const themeRoot = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const artistsPath = path.join(themeRoot, 'data', 'artists.json');

const fridayArtistIds = new Set([
  'sars',
  'alternosfera',
  'damian-and-brothers',
  'robin-and-the-backstabbers',
  'dj-ma-ta',
  'sar-casm',
  'calinacho',
  'idk',
  'lucawts',
  'oscar',
  'el-cartel-reggaeton-afterparty',
  'adrian-despot-and-cezar-popescu',
  'claudia-serdan-and-jimi-el-laco',
  'cypher',
  'damage-ctrl',
  'drew',
  'emu',
  'mandu',
  'oigan',
  'razy',
  'revan',
  'yoshuu',
  'disko-anksyete',
  'sear-bliss',
  'she',
  'sur-austru',
]);

const newArtists = [
  {
    id: 'oigan',
    name: 'Oigăn',
    level: 'level5',
    days: ['friday'],
    spotify_id: '6Ai2uEv68QVApaz7hfR2k6',
    spotify_url: 'https://open.spotify.com/artist/6Ai2uEv68QVApaz7hfR2k6',
    spotify_embed_url: 'https://open.spotify.com/embed/artist/6Ai2uEv68QVApaz7hfR2k6?utm_source=generator',
    image: 'https://i.scdn.co/image/ab6761610000e5eb09c7609d39078c74d922800b',
    image_override: '',
    genres: [],
    followers: null,
    popularity: null,
    description:
      'Oigăn (Eugen Nuțescu) este cantautor român, cofondator Kumm și membru Robin and the Backstabbers, cu un univers sonor ce combină indie, pop și experiment.',
    socials: {
      facebook: 'https://www.facebook.com/oigansongs',
      youtube: 'https://www.youtube.com/@oigan',
      tiktok: '',
      twitter: '',
      instagram: 'https://www.instagram.com/oigansongs/',
      spotify: 'https://open.spotify.com/artist/6Ai2uEv68QVApaz7hfR2k6',
    },
    spotify_name: 'Oigăn',
    images: [
      {
        url: 'https://i.scdn.co/image/ab6761610000e5eb09c7609d39078c74d922800b',
        height: 640,
        width: 640,
      },
    ],
  },
  {
    id: 'she',
    name: 'SHE',
    level: 'level6',
    days: ['friday'],
    spotify_id: '',
    spotify_url: '',
    spotify_embed_url: '',
    image: '',
    image_override: '',
    genres: [],
    followers: null,
    popularity: null,
    description: 'SHE este parte din lineup-ul Codru Festival. Detalii despre proiect vor fi anunțate.',
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

function withFridayDay(artist) {
  if (!fridayArtistIds.has(artist.id)) {
    return artist;
  }

  const days = normalizeDays(artist.days);
  if (!days.includes('friday')) {
    days.push('friday');
  }

  return {
    ...artist,
    days,
  };
}

const source = JSON.parse(await readFile(artistsPath, 'utf8'));
const artistsById = new Map((source.artists ?? []).map((artist) => [artist.id, withFridayDay(artist)]));

for (const artist of newArtists) {
  if (!artistsById.has(artist.id)) {
    artistsById.set(artist.id, artist);
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

const fridayCount = orderedArtists.filter((artist) => normalizeDays(artist.days).includes('friday')).length;
console.log(`Tagged ${fridayCount} artists for Friday in ${path.relative(process.cwd(), artistsPath)}`);
