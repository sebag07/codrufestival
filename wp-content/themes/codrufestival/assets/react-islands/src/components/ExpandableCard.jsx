import React, { useEffect, useId, useRef, useState } from 'react';

export function ExpandableCard({
  title,
  src,
  description,
  children,
  className = '',
  classNameExpanded = '',
}) {
  const [isExpanded, setIsExpanded] = useState(false);
  const closeButtonRef = useRef(null);
  const titleId = useId();

  useEffect(() => {
    if (!isExpanded) {
      return undefined;
    }

    const handleKeyDown = (event) => {
      if (event.key === 'Escape') {
        setIsExpanded(false);
      }
    };

    document.body.classList.add('codru-expandable-card-open');
    window.addEventListener('keydown', handleKeyDown);
    closeButtonRef.current?.focus();

    return () => {
      document.body.classList.remove('codru-expandable-card-open');
      window.removeEventListener('keydown', handleKeyDown);
    };
  }, [isExpanded]);

  return (
    <article className={`codru-expandable-card ${className}`.trim()}>
      <button
        type="button"
        className="codru-expandable-card__trigger"
        aria-expanded={isExpanded}
        onClick={() => setIsExpanded(true)}
      >
        <span className="codru-expandable-card__media" aria-hidden={!src}>
          {src ? (
            <img src={src} alt="" loading="lazy" />
          ) : (
            <span className="codru-expandable-card__media-fallback">{title?.charAt(0)}</span>
          )}
        </span>
        <span className="codru-expandable-card__summary">
          {description ? (
            <span className="codru-expandable-card__description">{description}</span>
          ) : null}
          <span className="codru-expandable-card__title">{title}</span>
          <span className="codru-expandable-card__hint">See more</span>
        </span>
      </button>

      {isExpanded ? (
        <div className="codru-expandable-card__overlay">
          <button
            type="button"
            className="codru-expandable-card__backdrop"
            aria-label="Close expanded card"
            onClick={() => setIsExpanded(false)}
          />
          <div
            className={`codru-expandable-card__panel ${classNameExpanded}`.trim()}
            role="dialog"
            aria-modal="true"
            aria-labelledby={titleId}
          >
            <button
              ref={closeButtonRef}
              type="button"
              className="codru-expandable-card__close"
              aria-label="Close expanded card"
              onClick={() => setIsExpanded(false)}
            >
              &times;
            </button>
            <div className="codru-expandable-card__expanded-media">
              {src ? <img src={src} alt="" /> : <span>{title?.charAt(0)}</span>}
            </div>
            <div className="codru-expandable-card__expanded-content">
              {description ? (
                <p className="codru-expandable-card__expanded-description">{description}</p>
              ) : null}
              <h3 id={titleId}>{title}</h3>
              <div className="codru-expandable-card__body">{children}</div>
            </div>
          </div>
        </div>
      ) : null}
    </article>
  );
}
