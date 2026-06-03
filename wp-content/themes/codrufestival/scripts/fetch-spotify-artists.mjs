import { readFile, writeFile } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const themeRoot = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const artistsPath = path.join(themeRoot, 'data', 'artists.json');
const envPath = path.join(themeRoot, '.env');

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

await loadEnvFile(envPath);

const clientId = process.env.SPOTIFY_CLIENT_ID;
const clientSecret = process.env.SPOTIFY_CLIENT_SECRET;

if (!clientId || !clientSecret) {
  console.error('Missing SPOTIFY_CLIENT_ID or SPOTIFY_CLIENT_SECRET.');
  console.error('Add both values to .env in the theme root, then run npm run artists:spotify.');
  process.exit(1);
}

async function requestAccessToken() {
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

async function requestArtist(token, artistId) {
  const response = await fetch(`https://api.spotify.com/v1/artists/${artistId}`, {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  });

  if (!response.ok) {
    throw new Error(`Spotify artist ${artistId} failed: ${response.status} ${await response.text()}`);
  }

  return response.json();
}

const source = JSON.parse(await readFile(artistsPath, 'utf8'));
const token = await requestAccessToken();
const hydratedArtists = [];

for (const artist of source.artists ?? []) {
  if (!artist.spotify_id) {
    console.warn(`Skipping ${artist.name}: spotify_id is empty.`);
    hydratedArtists.push(artist);
    continue;
  }

  const spotifyArtist = await requestArtist(token, artist.spotify_id);
  const spotifyUrl = spotifyArtist.external_urls?.spotify ?? `https://open.spotify.com/artist/${artist.spotify_id}`;

  hydratedArtists.push({
    ...artist,
    name: artist.name || spotifyArtist.name,
    spotify_name: spotifyArtist.name,
    spotify_url: spotifyUrl,
    spotify_embed_url: `https://open.spotify.com/embed/artist/${artist.spotify_id}?utm_source=generator`,
    image: spotifyArtist.images?.[0]?.url ?? '',
    image_override: artist.image_override ?? '',
    images: spotifyArtist.images ?? [],
    genres: spotifyArtist.genres ?? [],
    followers: spotifyArtist.followers?.total ?? null,
    popularity: spotifyArtist.popularity ?? null,
  });

  console.log(`Hydrated ${artist.name || spotifyArtist.name}`);
}

await writeFile(
  artistsPath,
  `${JSON.stringify(
    {
      ...source,
      updated_at: new Date().toISOString(),
      artists: hydratedArtists,
    },
    null,
    2,
  )}\n`,
);

console.log(`Updated ${path.relative(process.cwd(), artistsPath)}`);
