import React from 'react';
import { ExpandableCard } from './ExpandableCard.jsx';

const defaultDetails = 'More information about this artist will be added soon.';

const compact = (items) => items.filter(Boolean);
const socialLabels = {
  facebook: 'Facebook',
  youtube: 'YouTube',
  twitter: 'Twitter',
  instagram: 'Instagram',
  spotify: 'Spotify',
};

const formatNumber = (value) => {
  if (typeof value !== 'number') {
    return '';
  }

  return new Intl.NumberFormat('en-US').format(value);
};

function ArtistMeta({ label, value }) {
  if (!value) {
    return null;
  }

  return (
    <p className="codru-artist-expandable-cards__meta-item">
      <span>{label}</span>
      <strong>{value}</strong>
    </p>
  );
}

function ArtistSocialLinks({ socials = {} }) {
  const links = Object.entries(socialLabels)
    .map(([platform, label]) => ({
      platform,
      label,
      url: socials?.[platform],
    }))
    .filter((social) => social.url);

  if (!links.length) {
    return null;
  }

  return (
    <div className="codru-artist-expandable-cards__socials" aria-label="Artist social links">
      {links.map((social) => (
        <a
          key={social.platform}
          className="codru-artist-expandable-cards__social-link"
          href={social.url}
          target="_blank"
          rel="noreferrer"
        >
          {social.label}
        </a>
      ))}
    </div>
  );
}

export function ArtistExpandableCards({
  artists = [],
  eyebrow = 'Lineup',
  emptyText = 'Artists will be announced soon.',
}) {
  if (!artists.length) {
    return <p className="codru-island codru-artist-expandable-cards__empty">{emptyText}</p>;
  }

  return (
    <div className="codru-island codru-artist-expandable-cards">
      {artists.map((artist, index) => {
        const performanceDay = artist.day || artist.dayLabel || artist.schedule || 'Day TBA';
        const description = compact([performanceDay, artist.stage]).join(' | ');
        const genres = Array.isArray(artist.genres) ? artist.genres.join(', ') : '';
        const spotifyLinkText = artist.spotifyUrl ? 'Open on Spotify' : 'Open artist page';

        return (
          <ExpandableCard
            key={artist.id || `${artist.title}-${index}`}
            title={artist.title}
            src={artist.image}
            description={description || eyebrow}
            className="codru-artist-expandable-cards__card"
            classNameExpanded="codru-artist-expandable-cards__expanded"
          >
            <div className="codru-artist-expandable-cards__meta">
              <ArtistMeta label="Day" value={performanceDay} />
              <ArtistMeta label="Stage" value={artist.stage} />
              <ArtistMeta label="Schedule" value={artist.schedule} />
              <ArtistMeta label="Genres" value={genres} />
              <ArtistMeta label="Spotify followers" value={formatNumber(artist.followers)} />
              <ArtistMeta label="Spotify popularity" value={artist.popularity ? `${artist.popularity}/100` : ''} />
            </div>
            <p className="codru-artist-expandable-cards__details">
              {artist.details || defaultDetails}
            </p>
            <ArtistSocialLinks socials={artist.socials} />
            {artist.spotifyEmbedUrl ? (
              <iframe
                className="codru-artist-expandable-cards__spotify"
                title={`${artist.title} on Spotify`}
                src={artist.spotifyEmbedUrl}
                loading="lazy"
                allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
              />
            ) : null}
            {artist.link ? (
              <a className="codru-artist-expandable-cards__link" href={artist.link} target="_blank" rel="noreferrer">
                {spotifyLinkText}
              </a>
            ) : null}
          </ExpandableCard>
        );
      })}
    </div>
  );
}
