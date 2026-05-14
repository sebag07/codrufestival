import React from 'react';
import { ExpandableCard } from './ExpandableCard.jsx';

const defaultDetails = 'More information about this artist will be added soon.';

const compact = (items) => items.filter(Boolean);

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
        const description = compact([artist.level, artist.stage, artist.schedule]).join(' | ');

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
              <ArtistMeta label="Stage" value={artist.stage} />
              <ArtistMeta label="Schedule" value={artist.schedule} />
              <ArtistMeta label="Lineup" value={artist.level} />
            </div>
            <p className="codru-artist-expandable-cards__details">
              {artist.details || defaultDetails}
            </p>
            {artist.link ? (
              <a className="codru-artist-expandable-cards__link" href={artist.link}>
                Open artist page
              </a>
            ) : null}
          </ExpandableCard>
        );
      })}
    </div>
  );
}
