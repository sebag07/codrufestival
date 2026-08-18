import React, { useMemo, useState } from 'react';
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

function artistMatchesDay(artist, activeFilter) {
  if (activeFilter === 'all') {
    return true;
  }

  const days = Array.isArray(artist.days) ? artist.days : [];
  return days.includes(activeFilter);
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
  filters = [],
  eyebrow = 'Lineup',
  emptyText = 'Artists will be announced soon.',
  filteredEmptyText = 'No artists scheduled for this day yet.',
  showFilters = false,
  showDayLabels = false,
  showPerformanceMeta = true,
}) {
  const [activeFilter, setActiveFilter] = useState('all');

  const visibleArtists = useMemo(() => {
    if (!showFilters || activeFilter === 'all') {
      return artists;
    }

    return artists.filter((artist) => artistMatchesDay(artist, activeFilter));
  }, [activeFilter, artists, showFilters]);

  if (!artists.length) {
    return <p className="codru-island codru-artist-expandable-cards__empty">{emptyText}</p>;
  }

  return (
    <div className="codru-island codru-artist-expandable-cards__layout">
      {showFilters && filters.length ? (
        <div className="codru-artist-expandable-cards__filters" role="tablist" aria-label="Filter artists by day">
          {filters.map((filter) => {
            const isActive = activeFilter === filter.id;

            return (
              <button
                key={filter.id}
                type="button"
                role="tab"
                aria-selected={isActive}
                className={`codru-artist-expandable-cards__filter${isActive ? ' is-active' : ''}`}
                onClick={() => setActiveFilter(filter.id)}
              >
                {filter.label}
              </button>
            );
          })}
        </div>
      ) : null}

      {!visibleArtists.length ? (
        <p className="codru-artist-expandable-cards__empty">{filteredEmptyText}</p>
      ) : (
        <div className="codru-artist-expandable-cards">
          {visibleArtists.map((artist, index) => {
            const dayLabel = showDayLabels ? artist.dayLabel || artist.day || '' : '';
            const performanceMeta = compact([
              showPerformanceMeta ? artist.schedule : null,
              showPerformanceMeta ? artist.stage : null,
            ]);
            const description = showDayLabels
              ? dayLabel
              : showPerformanceMeta
                ? performanceMeta.join(' | ')
                : '';

            const hasSpotifyLink = Boolean(artist.spotifyUrl || artist.socials?.spotify);
            const expandable = artist.expandable ?? hasSpotifyLink;

            return (
              <ExpandableCard
                key={artist.id || `${artist.title}-${index}`}
                title={artist.title}
                src={artist.image}
                description={description}
                expandable={expandable}
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
      )}
    </div>
  );
}
