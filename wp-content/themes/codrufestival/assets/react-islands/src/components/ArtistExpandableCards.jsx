import React from 'react';
import { ExpandableCard } from './ExpandableCard.jsx';

const defaultDetails = 'More information about this artist will be added soon.';

const compact = (items) => items.filter(Boolean);
const socialLabels = {
  facebook: 'Facebook',
  youtube: 'YouTube',
  tiktok: 'TikTok',
  twitter: 'Twitter',
  instagram: 'Instagram',
  spotify: 'Spotify',
};

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
  showPerformanceMeta = true,
}) {
  if (!artists.length) {
    return <p className="codru-island codru-artist-expandable-cards__empty">{emptyText}</p>;
  }

  return (
    <div className="codru-island codru-artist-expandable-cards">
      {artists.map((artist, index) => {
        const performanceMeta = compact([artist.day || artist.dayLabel || artist.schedule, artist.stage]);
        const description = showPerformanceMeta ? performanceMeta.join(' | ') : '';

        return (
          <ExpandableCard
            key={artist.id || `${artist.title}-${index}`}
            title={artist.title}
            src={artist.image}
            description={description}
            className="codru-artist-expandable-cards__card"
            classNameExpanded="codru-artist-expandable-cards__expanded"
          >
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
          </ExpandableCard>
        );
      })}
    </div>
  );
}
