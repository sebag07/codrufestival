import React from 'react';

export function NewsletterSignupTeaser({
  title = 'Stay close to CODRU',
  text = 'Get festival updates, lineup news, and ticket announcements.',
  buttonText = 'Subscribe',
  href = '#newsletter',
}) {
  return (
    <section className="codru-island codru-newsletter-teaser">
      <div>
        <h2>{title}</h2>
        <p>{text}</p>
      </div>
      <a href={href}>{buttonText}</a>
    </section>
  );
}
