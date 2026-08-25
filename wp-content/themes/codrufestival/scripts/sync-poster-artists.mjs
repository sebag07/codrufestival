import { readFile, writeFile } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const themeRoot = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const artistsPath = path.join(themeRoot, 'data', 'artists.json');
const envPath = path.join(themeRoot, '.env');

const explicitSpotifyIds = {
  ian: '0GoJXmDr5UBG8ValCZe4om',
  calinacho: '050D4ZE1dXVfLSrQADtEu3',
  'acoustic-boyz': '1hgRAL5aWgNM6xutE503xU',
  'adrian-despot-and-cezar-popescu': '1IYl7TlgZV3jgeSHJnwL6i',
  asaway: '1oStBLD61F5umNc3uVRlvJ',
  'bob-ramanka': '5nidlwCOFD0dJUe8pTMNvs',
  ciresan: '0Mj28B7EnKRpq7SzirqvNy',
  exob: '5W4O4cLR1eyo99R0em8Bl6',
  kayf: '3zXRgHHOZxIDLwKLFyDuj5',
  'oigan': '6Ai2uEv68QVApaz7hfR2k6',
  tano: '1c2gYyQ4nnSEAEo2rLlFHE',
  tulvan: '2EVfpug360UAZHRgOKBdNg',
  zo: '6gOAgpuNfz5XqkzwdtkoOZ',
  blutrina: '4lN4TMRgzYgmhlrvMYvp8B',
  doomnezeu: '5hpGBeQ30Vhr9a8mUkRFtz',
  dordeduh: '052X0cOZM6KscHPcJwsPh0',
  'ordinul-negru': '5ZXKCnx6tV6Z3iGeKQihPI',
  'sear-bliss': '5h1vktUNKEYPDaQUUaOKAA',
  'sur-austru': '029YQisxpHZN8R3CxONBZ7',
};

const pressKitUrls = {
  'adrian-despot-and-cezar-popescu':
    'https://drive.google.com/file/d/1ArvCcd42-Pdq0CJsiEBqEqIRgne7Gas0/view?usp=drivesdk',
  paulvlj:
    'https://www.dropbox.com/scl/fo/srlfqlkdixrzfwpipd2bb/AH0ExZ9aXHxJZbBrxxhYy48?rlkey=kskofgd6fpc8bgqkn3hnm2lyd&st=pac3robt&dl=0',
  'sar-casm': 'https://drive.google.com/drive/folders/1HLr1WzTFne5NuSGpoHkRb-H_hUra3v6y',
};

const imageOverrides = {
  'adrian-despot-and-cezar-popescu': 'images/artists/adi-despot.jpg',
  'dj-ma-ta': 'images/artists/dj-ma-ta.jpg',
  'gipsy-kings': 'images/artists/gipsy-kings.jpeg',
  paulvlj: 'images/artists/paulvjj.png',
  'sar-casm': 'images/artists/sar-casm.jpg',
};

const skipSpotifySearchIds = new Set([
  'dj-ma-ta',
  'balkandreea-and-the-balkan-sisters',
  'el-cartel-reggaeton-afterparty',
  '4awl',
  'adrian-despot-and-cezar-popescu',
  'bia',
  'claudia-serdan-and-jimi-el-laco',
  'cypher',
  'damage-ctrl',
  'drew',
  'emu',
  'leanu',
  'mandu',
  'mini-zuchini',
  'narciss',
  'paulvlj',
  'raul-prodan',
  'razy',
  'revan',
  'sar-casm',
  'soulmaze',
  'ufo',
  'yoshuu',
  'zoleevee',
  'disko-anksyete',
  'emo-reunion',
  'nocturn',
]);

const posterLineup = [
  { id: 'gipsy-kings', name: 'Gipsy Kings <br> <small>FEATURIN TONINO BALIARDO</small>', level: 'level1' },
  { id: 'sars', name: 'S.A.R.S.', level: 'level2' },
  { id: 'ternipe', name: 'Ternipe', level: 'level2' },
  { id: 'alternosfera', name: 'Alternosfera', level: 'level3' },
  { id: 'subcarpati', name: 'Subcarpați', level: 'level3' },
  { id: 'puya', name: 'Puya', level: 'level3' },
  { id: 'surorile-osoianu-si-lupii-lui-calancea', name: 'Lupii Lui Calancea & Surorile Osoianu', level: 'level3' },
  { id: 'damian-and-brothers', name: 'Damian & Brothers', level: 'level3' },
  { id: 'e-an-na', name: 'E-AN-NA', level: 'level3' },
  { id: 'hvnds', name: 'HVNDS', level: 'level3' },
  { id: 'robin-and-the-backstabbers', name: 'Robin and the Backstabbers', level: 'level3' },
  {
    id: 'dj-ma-ta',
    name: 'DJ MĂ-TA',
    level: 'level3',
    skipSpotifySearch: true,
  },
  { id: 'balkandreea-and-the-balkan-sisters', name: 'Balkandreea & The Balkan Sisters', level: 'level3' },
  { id: 'adelin-mm', name: 'Adelin mm', level: 'level4' },
  { id: 'albert-nbn', name: 'Albert NBN', level: 'level4' },
  { id: 'calinacho', name: 'Calinacho', level: 'level4' },
  { id: 'ian', name: 'Ian', level: 'level4' },
  { id: 'idk', name: 'IDK', level: 'level4' },
  { id: 'lucawts', name: 'LUCAWTS', level: 'level4' },
  { id: 'mgk', name: 'MGK', level: 'level4' },
  { id: 'noua-unspe', name: 'NOUA UNSPE', level: 'level4' },
  { id: 'oscar', name: 'OSCAR', level: 'level4' },
  { id: 'petre-stefan', name: 'Petre Stefan', level: 'level4' },
  { id: 'rava', name: 'Rava', level: 'level4' },
  { id: 'vanilla', name: 'VANILLA', level: 'level4' },
  { id: 'el-cartel-reggaeton-afterparty', name: 'El Cartel Reggaeton Afterparty', level: 'level4' },
  { id: '4awl', name: '4AWL', level: 'level5' },
  { id: 'acoustic-boyz', name: 'Acoustic Boyz', level: 'level5' },
  { id: 'adrian-despot-and-cezar-popescu', name: 'Adrian Despot & Cezar Popescu', level: 'level5' },
  { id: 'asaway', name: 'Asaway', level: 'level5' },
  { id: 'bia', name: 'Bia & Mc Geeza', level: 'level5' },
  { id: 'bob-ramanka', name: 'Bob Ramanka', level: 'level5' },
  { id: 'ciresan', name: 'Cireșan', level: 'level5' },
  { id: 'claudia-serdan-and-jimi-el-laco', name: 'Claudia Șerdan & Jimmi El Laco', level: 'level5' },
  { id: 'cypher', name: 'Cypher', level: 'level5' },
  { id: 'damage-ctrl', name: 'DAMAGE:CTRL', level: 'level5' },
  { id: 'drew', name: 'Drew', level: 'level5' },
  { id: 'emu', name: 'Emu', level: 'level5' },
  { id: 'exob', name: 'EXOB', level: 'level5' },
  { id: 'kayf', name: 'Kayf', level: 'level5' },
  { id: 'leanu', name: 'Leanu', level: 'level5' },
  { id: 'mandu', name: 'Mandu', level: 'level5' },
  { id: 'mini-zucchini', name: 'Mini Zucchini', level: 'level5' },
  { id: 'narciss', name: 'Narciss', level: 'level5' },
  { id: 'oigan', name: 'Oigăn', level: 'level5' },
  { id: 'paulvlj', name: 'PaulVLJ', level: 'level5' },
  { id: 'raul-prodan', name: 'Raul Prodan', level: 'level5' },
  { id: 'razy', name: 'Razy', level: 'level5' },
  { id: 'revan', name: 'Revan', level: 'level5' },
  { id: 'sar-casm', name: 'SAR.CASM', level: 'level5' },
  { id: 'soulmaze', name: 'Soulmaze', level: 'level5' },
  { id: 'tano', name: 'Tano', level: 'level5' },
  { id: 'tulvan', name: 'Tulvan', level: 'level5' },
  { id: 'ufo', name: 'UFO', level: 'level5' },
  { id: 'yoshuu', name: 'Yoshuu', level: 'level5' },
  { id: 'zo', name: 'ZO', level: 'level5' },
  { id: 'zoleevee', name: 'Zoleevee', level: 'level5' },
  { id: 'blutrina', name: 'Blutrină', level: 'level6' },
  { id: 'disko-anksyete', name: 'Disko Anksyete', level: 'level6' },
  { id: 'doomnezeu', name: 'Doomnezeu', level: 'level6' },
  { id: 'dordeduh', name: 'Dordeduh', level: 'level6' },
  { id: 'emo-reunion', name: 'Emo Reunion', level: 'level6' },
  { id: 'nocturn', name: 'Nocturn', level: 'level6' },
  { id: 'ordinul-negru', name: 'Ordinul Negru', level: 'level6' },
  { id: 'sear-bliss', name: 'Sear Bliss', level: 'level6' },
  { id: 'sur-austru', name: 'Sur Austru', level: 'level6' },
];

const searchOverrides = {
  mgk: 'Mgk666',
  idk: 'IDK Romania rapper',
  oscar: 'Oscar Romania rapper',
};

function clearSpotifyMetadata(artist) {
  return {
    ...artist,
    spotify_id: '',
    spotify_url: '',
    spotify_embed_url: '',
    image: artist.image_override ? artist.image : '',
    genres: [],
    followers: null,
    popularity: null,
    spotify_name: undefined,
    images: undefined,
    socials: {
      ...(artist.socials ?? {}),
      spotify: '',
    },
  };
}

function stripHtml(value) {
  return String(value ?? '').replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
}

async function loadEnvFile(filePath) {
  let contents = '';

  try {
    contents = await readFile(filePath, 'utf8');
  } catch (error) {
    if (error.code === 'ENOENT') {
      return;
    }

    throw error;
  }

  for (const line of contents.split(/\r?\n/)) {
    const trimmedLine = line.trim();

    if (!trimmedLine || trimmedLine.startsWith('#')) {
      continue;
    }

    const separatorIndex = trimmedLine.indexOf('=');
    if (separatorIndex === -1) {
      continue;
    }

    const key = trimmedLine.slice(0, separatorIndex).trim().replace(/^export\s+/, '');
    let value = trimmedLine.slice(separatorIndex + 1).trim();

    if (!key || process.env[key] !== undefined) {
      continue;
    }

    if (
      (value.startsWith('"') && value.endsWith('"')) ||
      (value.startsWith("'") && value.endsWith("'"))
    ) {
      value = value.slice(1, -1);
    }

    process.env[key] = value;
  }
}

function createEmptyArtist(entry) {
  return {
    id: entry.id,
    name: entry.name,
    level: entry.level,
    spotify_id: entry.spotify_id ?? '',
    spotify_url: entry.spotify_url ?? '',
    spotify_embed_url: entry.spotify_embed_url ?? '',
    image: '',
    image_override: '',
    genres: [],
    followers: null,
    popularity: null,
    description: '',
    press_kit_url: '',
    socials: {
      facebook: '',
      youtube: '',
      tiktok: '',
      twitter: '',
      instagram: '',
      spotify: entry.spotify_url ?? '',
    },
  };
}

async function requestAccessToken(clientId, clientSecret) {
  const response = await fetch('https://accounts.spotify.com/api/token', {
    method: 'POST',
    headers: {
      Authorization: `Basic ${Buffer.from(`${clientId}:${clientSecret}`).toString('base64')}`,
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: new URLSearchParams({ grant_type: 'client_credentials' }),
  });

  if (!response.ok) {
    throw new Error(`Spotify auth failed: ${response.status} ${await response.text()}`);
  }

  const body = await response.json();
  return body.access_token;
}

async function searchArtist(token, query) {
  const params = new URLSearchParams({
    q: query,
    type: 'artist',
    limit: '5',
    market: 'RO',
  });

  const response = await fetch(`https://api.spotify.com/v1/search?${params.toString()}`, {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  });

  if (!response.ok) {
    throw new Error(`Spotify search "${query}" failed: ${response.status} ${await response.text()}`);
  }

  const body = await response.json();
  return body.artists?.items ?? [];
}

await loadEnvFile(envPath);

const clientId = process.env.SPOTIFY_CLIENT_ID;
const clientSecret = process.env.SPOTIFY_CLIENT_SECRET;
const source = JSON.parse(await readFile(artistsPath, 'utf8'));
const existingById = new Map((source.artists ?? []).map((artist) => [artist.id, artist]));
const mergedArtists = [];
let token = null;

if (clientId && clientSecret) {
  token = await requestAccessToken(clientId, clientSecret);
}

for (const entry of posterLineup) {
  const existing = existingById.get(entry.id);
  let artist = existing
    ? {
        ...existing,
        name: entry.name,
        level: entry.level,
      }
    : createEmptyArtist(entry);

  if (entry.skipSpotifySearch || skipSpotifySearchIds.has(entry.id)) {
    if (!explicitSpotifyIds[entry.id]) {
      artist = clearSpotifyMetadata(artist);
    }
  }

  if (explicitSpotifyIds[entry.id]) {
    artist.spotify_id = explicitSpotifyIds[entry.id];
  }

  if (pressKitUrls[entry.id]) {
    artist.press_kit_url = pressKitUrls[entry.id];
  }

  if (imageOverrides[entry.id]) {
    artist.image_override = imageOverrides[entry.id];
  }

  if (entry.skipSpotifySearch) {
    mergedArtists.push(artist);
    continue;
  }

  if (skipSpotifySearchIds.has(entry.id)) {
    mergedArtists.push(artist);
    continue;
  }

  if (!artist.spotify_id && token) {
    const query = searchOverrides[entry.id] ?? stripHtml(entry.name);
    const results = await searchArtist(token, query);
    const match = results.find((item) => stripHtml(item.name).toLowerCase() === stripHtml(entry.name).toLowerCase()) ?? results[0];

    if (match?.id) {
      artist.spotify_id = match.id;
      console.log(`Matched ${entry.name} -> ${match.name} (${match.id})`);
    } else {
      console.warn(`No Spotify match for ${entry.name} (${query})`);
    }
  }

  mergedArtists.push(artist);
}

await writeFile(
  artistsPath,
  `${JSON.stringify(
    {
      ...source,
      updated_at: new Date().toISOString(),
      artists: mergedArtists,
    },
    null,
    2,
  )}\n`,
);

console.log(`Synced ${mergedArtists.length} poster artists to ${path.relative(process.cwd(), artistsPath)}`);
