import React, { useEffect, useMemo, useState } from 'react';

const getHashId = (installations) => {
  const hash = window.location.hash.replace('#', '');
  return installations.some((item) => item.id === hash) ? hash : '';
};

export function YouthInstallations({ installations = [], defaultId = '' }) {
  const items = useMemo(
    () => (Array.isArray(installations) ? installations.filter((item) => item?.id) : []),
    [installations]
  );
  const firstId = defaultId && items.some((item) => item.id === defaultId) ? defaultId : items[0]?.id || '';
  const [activeId, setActiveId] = useState(firstId);
  const [lightboxIndex, setLightboxIndex] = useState(-1);

  useEffect(() => {
    const syncFromHash = () => {
      const hashId = getHashId(items);
      if (hashId) {
        setActiveId(hashId);
      }
    };

    syncFromHash();
    window.addEventListener('hashchange', syncFromHash);
    return () => window.removeEventListener('hashchange', syncFromHash);
  }, [items]);

  const active = items.find((item) => item.id === activeId) || items[0];
  const images = Array.isArray(active?.images) ? active.images.filter((image) => image?.src) : [];
  const lightboxImage = lightboxIndex >= 0 ? images[lightboxIndex] : null;

  useEffect(() => {
    if (!lightboxImage) {
      document.body.classList.remove('codru-youth-lightbox-open');
      return undefined;
    }

    const handleKeyDown = (event) => {
      if (event.key === 'Escape') {
        setLightboxIndex(-1);
      } else if (event.key === 'ArrowRight' && images.length > 1) {
        setLightboxIndex((current) => (current + 1) % images.length);
      } else if (event.key === 'ArrowLeft' && images.length > 1) {
        setLightboxIndex((current) => (current - 1 + images.length) % images.length);
      }
    };

    document.body.classList.add('codru-youth-lightbox-open');
    window.addEventListener('keydown', handleKeyDown);
    return () => {
      document.body.classList.remove('codru-youth-lightbox-open');
      window.removeEventListener('keydown', handleKeyDown);
    };
  }, [lightboxImage, images.length]);

  const selectInstallation = (id) => {
    setActiveId(id);
    setLightboxIndex(-1);
    if (window.location.hash !== `#${id}`) {
      window.history.replaceState(null, '', `#${id}`);
    }
  };

  if (!items.length || !active) {
    return null;
  }

  return (
    <div className="codru-island codru-youth">
      <div className="codru-youth__tabs" role="tablist" aria-label="Instalații CODRU For Youth">
        {items.map((item) => {
          const isActive = item.id === active.id;
          return (
            <button
              key={item.id}
              type="button"
              role="tab"
              id={`youth-tab-${item.id}`}
              aria-selected={isActive}
              aria-controls={`youth-panel-${item.id}`}
              className={`codru-youth__tab${isActive ? ' is-active' : ''}`}
              onClick={() => selectInstallation(item.id)}
            >
              {item.label || item.title}
            </button>
          );
        })}
      </div>

      <section
        className="codru-youth__panel"
        role="tabpanel"
        id={`youth-panel-${active.id}`}
        aria-labelledby={`youth-tab-${active.id}`}
      >
        <header className="codru-youth__header">
          {active.kicker ? <p className="codru-youth__kicker">{active.kicker}</p> : null}
          <h2 className="codru-youth__title">{active.title}</h2>
          {active.subtitle ? <p className="codru-youth__subtitle">{active.subtitle}</p> : null}
          {Array.isArray(active.meta) && active.meta.length ? (
            <ul className="codru-youth__meta">
              {active.meta.filter(Boolean).map((entry) => (
                <li key={entry}>{entry}</li>
              ))}
            </ul>
          ) : null}
        </header>

        {active.description ? (
          <div className="codru-youth__description" dangerouslySetInnerHTML={{ __html: active.description }} />
        ) : null}

        {images.length ? (
          <div className="codru-youth__gallery">
            {images.map((image, index) => (
              <button
                key={`${image.src}-${index}`}
                type="button"
                className="codru-youth__shot"
                onClick={() => setLightboxIndex(index)}
              >
                <img src={image.src} alt={image.alt || active.title} loading="lazy" />
              </button>
            ))}
          </div>
        ) : null}
      </section>

      {lightboxImage ? (
        <div className="codru-youth__lightbox">
          <button
            type="button"
            className="codru-youth__lightbox-backdrop"
            aria-label="Închide imaginea"
            onClick={() => setLightboxIndex(-1)}
          />
          <figure className="codru-youth__lightbox-figure">
            <img src={lightboxImage.src} alt={lightboxImage.alt || active.title} />
            {lightboxImage.alt ? <figcaption>{lightboxImage.alt}</figcaption> : null}
          </figure>
          {images.length > 1 ? (
            <>
              <button
                type="button"
                className="codru-youth__lightbox-nav is-prev"
                aria-label="Imaginea anterioară"
                onClick={() => setLightboxIndex((current) => (current - 1 + images.length) % images.length)}
              >
                ‹
              </button>
              <button
                type="button"
                className="codru-youth__lightbox-nav is-next"
                aria-label="Imaginea următoare"
                onClick={() => setLightboxIndex((current) => (current + 1) % images.length)}
              >
                ›
              </button>
            </>
          ) : null}
          <button
            type="button"
            className="codru-youth__lightbox-close"
            aria-label="Închide imaginea"
            onClick={() => setLightboxIndex(-1)}
          >
            ×
          </button>
        </div>
      ) : null}
    </div>
  );
}
