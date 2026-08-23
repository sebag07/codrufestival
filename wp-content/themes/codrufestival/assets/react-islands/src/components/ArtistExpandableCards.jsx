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

function artistMatchesStage(artist, activeFilter) {
  if (activeFilter === 'all') {
    return true;
  }

  return artist.stage === activeFilter;
}

function buildArtistTitle(artist, activeDayFilter) {
  const baseTitle = artist.baseTitle || artist.title || '';
  const dayGuests = artist.dayGuests && typeof artist.dayGuests === 'object' ? artist.dayGuests : null;

  if (activeDayFilter === 'all' || !dayGuests) {
    return baseTitle;
  }

  const guestLabel = dayGuests[activeDayFilter];
  if (!guestLabel) {
    return baseTitle;
  }

  return `${baseTitle} <br> <small>${guestLabel}</small>`;
}

function buildDayLabel(artist, activeDayFilter, showDayLabels) {
  if (!showDayLabels) {
    return '';
  }

  if (activeDayFilter !== 'all') {
    const dayLabelByDay = artist.dayLabelByDay && typeof artist.dayLabelByDay === 'object' ? artist.dayLabelByDay : null;
    return dayLabelByDay?.[activeDayFilter] || '';
  }

  return artist.dayLabel || artist.day || '';
}

function buildCardDescription(artist, activeDayFilter, showDayLabels, showStageLabels) {
  const parts = compact([
    buildDayLabel(artist, activeDayFilter, showDayLabels),
    showStageLabels ? artist.stageLabel || '' : '',
  ]);

  return parts.join('<br>');
}

function FilterGroup({ filters, activeFilter, onChange, ariaLabel }) {
  if (!filters.length) {
    return null;
  }

  return (
    <div className="codru-artist-expandable-cards__filters" role="tablist" aria-label={ariaLabel}>
      {filters.map((filter) => {
        const isActive = activeFilter === filter.id;

        return (
          <button
            key={filter.id}
            type="button"
            role="tab"
            aria-selected={isActive}
            className={`codru-artist-expandable-cards__filter${isActive ? ' is-active' : ''}`}
            onClick={() => onChange(filter.id)}
          >
            {filter.label}
          </button>
        );
      })}
    </div>
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
  filters = [],
  stageFilters = [],
  eyebrow = 'Lineup',
  emptyText = 'Artists will be announced soon.',
  filteredEmptyText = 'No artists match the selected filters.',
  showFilters = false,
  showStageFilters = false,
  showDayLabels = false,
  showStageLabels = false,
  showPerformanceMeta = true,
}) {
  const [activeDayFilter, setActiveDayFilter] = useState('all');
  const [activeStageFilter, setActiveStageFilter] = useState('all');

  const visibleArtists = useMemo(() => {
    return artists.filter(
      (artist) =>
        artistMatchesDay(artist, activeDayFilter) && artistMatchesStage(artist, activeStageFilter),
    );
  }, [activeDayFilter, activeStageFilter, artists]);

  if (!artists.length) {
    return <p className="codru-island codru-artist-expandable-cards__empty">{emptyText}</p>;
  }

  return (
    <div className="codru-island codru-artist-expandable-cards__layout">
      {showFilters || showStageFilters ? (
        <div className="codru-artist-expandable-cards__filter-groups">
          {showFilters ? (
            <FilterGroup
              filters={filters}
              activeFilter={activeDayFilter}
              onChange={setActiveDayFilter}
              ariaLabel="Filter artists by day"
            />
          ) : null}
          {showStageFilters ? (
            <FilterGroup
              filters={stageFilters}
              activeFilter={activeStageFilter}
              onChange={setActiveStageFilter}
              ariaLabel="Filter artists by stage"
            />
          ) : null}
        </div>
      ) : null}

      {!visibleArtists.length ? (
        <p className="codru-artist-expandable-cards__empty">{filteredEmptyText}</p>
      ) : (
        <div className="codru-artist-expandable-cards">
          {visibleArtists.map((artist, index) => {
            const cardTitle = buildArtistTitle(artist, activeDayFilter);
            const performanceMeta = compact([
              showPerformanceMeta ? artist.schedule : null,
              showPerformanceMeta ? artist.stageLabel || artist.stage : null,
            ]);
            const description =
              showDayLabels || showStageLabels
                ? buildCardDescription(artist, activeDayFilter, showDayLabels, showStageLabels)
                : showPerformanceMeta
                  ? performanceMeta.join(' | ')
                  : '';

            const hasSpotifyLink = Boolean(artist.spotifyUrl || artist.socials?.spotify);
            const expandable = artist.expandable ?? hasSpotifyLink;

            return (
              <ExpandableCard
                key={artist.id || `${artist.title}-${index}`}
                title={cardTitle}
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
