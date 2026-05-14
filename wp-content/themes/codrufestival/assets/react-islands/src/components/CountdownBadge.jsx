import React, { useEffect, useMemo, useState } from 'react';

const getRemainingDays = (targetDate) => {
  const targetTime = new Date(targetDate).getTime();

  if (Number.isNaN(targetTime)) {
    return null;
  }

  const difference = targetTime - Date.now();
  return Math.max(0, Math.ceil(difference / (1000 * 60 * 60 * 24)));
};

export function CountdownBadge({
  targetDate,
  title = 'CODRU Festival',
  dayLabel = 'days left',
  expiredText = 'See you at CODRU',
}) {
  const [remainingDays, setRemainingDays] = useState(() => getRemainingDays(targetDate));
  const hasExpired = remainingDays === 0;
  const text = useMemo(() => {
    if (remainingDays === null) {
      return expiredText;
    }

    return hasExpired ? expiredText : `${remainingDays} ${dayLabel}`;
  }, [dayLabel, expiredText, hasExpired, remainingDays]);

  useEffect(() => {
    const timer = window.setInterval(() => {
      setRemainingDays(getRemainingDays(targetDate));
    }, 60 * 1000);

    return () => window.clearInterval(timer);
  }, [targetDate]);

  return (
    <div className="codru-island codru-countdown-badge">
      <span className="codru-countdown-badge__title">{title}</span>
      <strong className="codru-countdown-badge__value">{text}</strong>
    </div>
  );
}
