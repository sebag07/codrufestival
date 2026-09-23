import React, { useEffect, useState } from 'react';

export function FestivalGallery({ images = [] }) {
  const photos = Array.isArray(images) ? images.filter((image) => image?.src) : [];
  const [lightboxIndex, setLightboxIndex] = useState(-1);
  const lightboxImage = lightboxIndex >= 0 ? photos[lightboxIndex] : null;

  useEffect(() => {
    if (!lightboxImage) {
      document.body.classList.remove('codru-gallery-lightbox-open');
      return undefined;
    }

    const handleKeyDown = (event) => {
      if (event.key === 'Escape') {
        setLightboxIndex(-1);
      } else if (event.key === 'ArrowRight' && photos.length > 1) {
        setLightboxIndex((current) => (current + 1) % photos.length);
      } else if (event.key === 'ArrowLeft' && photos.length > 1) {
        setLightboxIndex((current) => (current - 1 + photos.length) % photos.length);
      }
    };

    document.body.classList.add('codru-gallery-lightbox-open');
    window.addEventListener('keydown', handleKeyDown);
    return () => {
      document.body.classList.remove('codru-gallery-lightbox-open');
      window.removeEventListener('keydown', handleKeyDown);
    };
  }, [lightboxImage, photos.length]);

  if (!photos.length) {
    return null;
  }

  return (
    <div className="codru-island codru-festival-gallery">
      <div className="codru-festival-gallery__grid">
        {photos.map((image, index) => (
          <button
            key={`${image.src}-${index}`}
            type="button"
            className="codru-festival-gallery__shot"
            onClick={() => setLightboxIndex(index)}
          >
            <img src={image.src} alt={image.alt || ''} loading="lazy" />
          </button>
        ))}
      </div>

      {lightboxImage ? (
        <div className="codru-festival-gallery__lightbox">
          <button
            type="button"
            className="codru-festival-gallery__lightbox-backdrop"
            aria-label="Închide imaginea"
            onClick={() => setLightboxIndex(-1)}
          />
          <div className="codru-festival-gallery__lightbox-stage">
            {photos.length > 1 ? (
              <button
                type="button"
                className="codru-festival-gallery__lightbox-nav is-prev"
                aria-label="Imaginea anterioară"
                onClick={() => setLightboxIndex((current) => (current - 1 + photos.length) % photos.length)}
              >
                <svg viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M15 5 8 12l7 7" fill="none" stroke="currentColor" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round" />
                </svg>
              </button>
            ) : null}
            <figure className="codru-festival-gallery__lightbox-figure">
              <img src={lightboxImage.full || lightboxImage.src} alt={lightboxImage.alt || ''} />
            </figure>
            {photos.length > 1 ? (
              <button
                type="button"
                className="codru-festival-gallery__lightbox-nav is-next"
                aria-label="Imaginea următoare"
                onClick={() => setLightboxIndex((current) => (current + 1) % photos.length)}
              >
                <svg viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M9 5 16 12 9 19" fill="none" stroke="currentColor" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round" />
                </svg>
              </button>
            ) : null}
          </div>
          <button
            type="button"
            className="codru-festival-gallery__lightbox-close"
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
