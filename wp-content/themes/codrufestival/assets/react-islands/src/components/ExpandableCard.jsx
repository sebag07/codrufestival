import React, { Fragment, useEffect, useId, useRef, useState } from 'react';
import { AnimatePresence, motion } from 'motion/react';

const stripNameMarkup = (value) => String(value ?? '').replace(/<br\s*\/?>/gi, ' ').replace(/<\/?small>/gi, '');

function TitleText({ value }) {
  const renderBreaks = (text, keyPrefix) =>
    text
    .split(/<br\s*\/?>/gi)
    .map((part, index, parts) => (
      <Fragment key={`${keyPrefix}-${index}`}>
        {part}
        {index < parts.length - 1 ? <br /> : null}
      </Fragment>
    ));

  return String(value ?? '')
    .split(/(<small>[\s\S]*?<\/small>)/gi)
    .map((part, index) => {
      const smallMatch = part.match(/^<small>([\s\S]*?)<\/small>$/i);

      if (smallMatch) {
        return <small key={`small-${index}`}>{renderBreaks(smallMatch[1], `small-${index}`)}</small>;
      }

      return <Fragment key={`text-${index}`}>{renderBreaks(part, `text-${index}`)}</Fragment>;
    });
}

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
  const layoutId = useId();
  const plainTitle = stripNameMarkup(title);

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
      <motion.button
        layoutId={`card-${layoutId}`}
        type="button"
        className="codru-expandable-card__trigger"
        aria-expanded={isExpanded}
        onClick={() => setIsExpanded(true)}
      >
        <motion.span layoutId={`media-${layoutId}`} className="codru-expandable-card__media" aria-hidden={!src}>
          {src ? (
            <img src={src} alt="" loading="lazy" />
          ) : (
            <span className="codru-expandable-card__media-fallback">{plainTitle?.charAt(0)}</span>
          )}
        </motion.span>
        <span className="codru-expandable-card__summary">
          {description ? (
            <motion.span layoutId={`description-${layoutId}`} className="codru-expandable-card__description">
              {description}
            </motion.span>
          ) : null}
          <motion.span layoutId={`title-${layoutId}`} className="codru-expandable-card__title">
            <TitleText value={title} />
          </motion.span>
        </span>
      </motion.button>

      <AnimatePresence>
        {isExpanded ? (
          <motion.div className="codru-expandable-card__overlay">
            <motion.button
              type="button"
              className="codru-expandable-card__backdrop"
              aria-label="Close expanded card"
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              transition={{ duration: 0.2 }}
              onClick={() => setIsExpanded(false)}
            />
            <motion.div
              layoutId={`card-${layoutId}`}
              className={`codru-expandable-card__panel ${classNameExpanded}`.trim()}
              role="dialog"
              aria-modal="true"
              aria-labelledby={titleId}
              transition={{ type: 'spring', damping: 26, stiffness: 240 }}
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
              <motion.div layoutId={`media-${layoutId}`} className="codru-expandable-card__expanded-media">
                {src ? <img src={src} alt="" /> : <span>{plainTitle?.charAt(0)}</span>}
                <div className="codru-expandable-card__expanded-heading">
                  {description ? (
                    <motion.p layoutId={`description-${layoutId}`} className="codru-expandable-card__expanded-description">
                      {description}
                    </motion.p>
                  ) : null}
                  <motion.h3 layoutId={`title-${layoutId}`} id={titleId}>
                    <TitleText value={title} />
                  </motion.h3>
                </div>
              </motion.div>
              <div className="codru-expandable-card__expanded-content">
                <motion.div
                  className="codru-expandable-card__body"
                  initial={{ opacity: 0, y: 16 }}
                  animate={{ opacity: 1, y: 0 }}
                  exit={{ opacity: 0, y: 8 }}
                  transition={{ duration: 0.18, delay: 0.08 }}
                >
                  {children}
                </motion.div>
              </div>
            </motion.div>
          </motion.div>
        ) : null}
      </AnimatePresence>
    </article>
  );
}
