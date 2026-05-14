import React from 'react';

export function BrandCultureCards({ values = [], emptyText = 'Values will be added soon.' }) {
  if (!values.length) {
    return <p className="codru-island codru-brand-culture-cards__empty">{emptyText}</p>;
  }

  return (
    <div className="codru-island codru-brand-culture-cards">
      {values.map((value, index) => (
        <article
          key={value.id || `${value.title}-${index}`}
          className={`codru-brand-culture-card ${value.useDarkText ? 'codru-brand-culture-card--dark-text' : ''}`.trim()}
        >
          {value.image ? (
            <div className="codru-brand-culture-card__media">
              <img src={value.image} alt="" loading="lazy" />
            </div>
          ) : null}
          <div className="codru-brand-culture-card__content">
            {value.keywords ? <p className="codru-brand-culture-card__keywords">{value.keywords}</p> : null}
            <h3>{value.title}</h3>
            {value.description ? (
              <div
                className="codru-brand-culture-card__description"
                dangerouslySetInnerHTML={{ __html: value.description }}
              />
            ) : null}
          </div>
        </article>
      ))}
    </div>
  );
}
