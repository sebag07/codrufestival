import React, { useCallback, useEffect, useId, useMemo, useRef, useState } from 'react';
import { createPortal } from 'react-dom';
import wordmark from '../assets/spin-wheel/wordmark.png';

const WHEEL_CX = 150;
const WHEEL_CY = 150;
const WHEEL_RADIUS = 148;
const SPIN_TURNS = 6;
const SPIN_DURATION_MS = 4400;

const DEFAULT_SEGMENTS = [
  { pct: 20, hex: '#2ecc5a' },
  { pct: 25, hex: '#28e069' },
  { pct: 30, hex: '#1ff97a' },
  { pct: 15, hex: '#12ff6e' },
  { pct: 50, hex: '#22ff5c' },
  { pct: 10, hex: '#00ff41' },
];

const DEFAULT_COPY = {
  dates: '28–30 AUGUST',
  location: 'PĂDUREA VERDE, TIMIȘOARA',
  eyebrow: 'Feeling lucky?',
  titleBefore: 'Spin the ',
  titleAccent: 'CODRU Wheel',
  lede: 'Win up to 50% off your CODRU Festival ticket.',
  spinLabel: 'SPIN & WIN →',
  spinningLabel: 'SPINNING…',
  underWheel: "ONE SPIN. ONE DISCOUNT. DON'T WASTE IT.",
  resultBadge: 'YOU GOT',
  codeLabel: 'YOUR CODRU DISCOUNT CODE',
  copyLabel: 'COPY CODE',
  copiedLabel: 'COPIED ✓',
  copyFailedLabel: 'COPY FAILED',
  ctaLabel: 'GET YOUR TICKET',
  validity: 'Enter it in the voucher field at checkout.',
  closeLabel: 'Close',
  errorLabel: 'Something went wrong. Try again.',
  offLabel: 'OFF',
  dialogLabel: 'CODRU Wheel',
};

const polarToXY = (angleDeg, radius) => {
  const angle = ((angleDeg - 90) * Math.PI) / 180;
  return [WHEEL_CX + radius * Math.cos(angle), WHEEL_CY + radius * Math.sin(angle)];
};

const segmentPath = (index, count) => {
  const segmentAngle = 360 / count;
  const start = index * segmentAngle - segmentAngle / 2;
  const end = start + segmentAngle;
  const [x1, y1] = polarToXY(start, WHEEL_RADIUS);
  const [x2, y2] = polarToXY(end, WHEEL_RADIUS);
  const largeArc = segmentAngle > 180 ? 1 : 0;

  return `M ${WHEEL_CX} ${WHEEL_CY} L ${x1} ${y1} A ${WHEEL_RADIUS} ${WHEEL_RADIUS} 0 ${largeArc} 1 ${x2} ${y2} Z`;
};

const wheelTarget = (index, count) => {
  const center = index * (360 / count);
  return (360 - center) % 360;
};

const prefersReducedMotion = () => {
  if (typeof window === 'undefined' || typeof window.matchMedia !== 'function') {
    return false;
  }

  return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
};

const readStorage = (key) => {
  try {
    return window.localStorage.getItem(key);
  } catch (error) {
    return null;
  }
};

const writeStorage = (key, value) => {
  try {
    window.localStorage.setItem(key, value);
  } catch (error) {
    // Private mode and quota errors fail silently, matching the original widget.
  }
};

const hasBlockingStorage = (resultKey, dismissedKey) => {
  return Boolean(readStorage(resultKey) || readStorage(dismissedKey));
};

const persistResult = (resultKey, dismissedKey, prize) => {
  writeStorage(
    resultKey,
    JSON.stringify({
      pct: prize.pct,
      code: prize.code,
      expiresAt: null,
    }),
  );
  writeStorage(dismissedKey, '1');
};

const getCookiebot = () => window.Cookiebot || window.CookieConsent || null;

const isCookiebotDialogOpen = () => {
  const dialog = document.querySelector(
    '#CybotCookiebotDialog, #CookiebotDialog, .CybotCookiebotDialogActive, [id^="CybotCookiebotDialog"]',
  );

  if (!dialog) {
    return false;
  }

  const style = window.getComputedStyle(dialog);
  const isHidden =
    style.display === 'none' || style.visibility === 'hidden' || style.opacity === '0' || dialog.getAttribute('aria-hidden') === 'true';

  return !isHidden && dialog.getClientRects().length > 0;
};

const waitForCookiebot = () => {
  return new Promise((resolve) => {
    let settled = false;
    let intervalId = 0;
    let safetyId = 0;
    const startedAt = Date.now();

    const done = () => {
      if (settled) {
        return;
      }

      settled = true;
      window.clearInterval(intervalId);
      window.clearTimeout(safetyId);
      window.removeEventListener('CookiebotOnAccept', done);
      window.removeEventListener('CookiebotOnDecline', done);
      window.removeEventListener('CookiebotOnDialogClose', done);
      resolve();
    };

    const tryOpen = () => {
      const cookiebot = getCookiebot();

      if (cookiebot?.hasResponse) {
        done();
        return;
      }

      if (isCookiebotDialogOpen()) {
        return;
      }

      // Give Cookiebot a brief moment to paint a first-visit dialog.
      if (Date.now() - startedAt >= 600) {
        done();
      }
    };

    if (getCookiebot()?.hasResponse) {
      done();
      return;
    }

    window.addEventListener('CookiebotOnAccept', done);
    window.addEventListener('CookiebotOnDecline', done);
    window.addEventListener('CookiebotOnDialogClose', done);
    intervalId = window.setInterval(tryOpen, 200);
    safetyId = window.setTimeout(done, 8000);
    tryOpen();
  });
};

const requestSpin = async (endpoint) => {
  const response = await fetch(endpoint, {
    method: 'POST',
    credentials: 'same-origin',
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
    },
  });

  if (!response.ok) {
    throw new Error('spin_failed');
  }

  const data = await response.json();

  if (
    typeof data?.pct !== 'number' ||
    typeof data?.code !== 'string' ||
    typeof data?.index !== 'number'
  ) {
    throw new Error('spin_invalid');
  }

  return data;
};

const copyText = async (value) => {
  try {
    if (navigator.clipboard?.writeText) {
      await navigator.clipboard.writeText(value);
      return true;
    }
  } catch (error) {
    // Fall through to the legacy copy path.
  }

  try {
    const textarea = document.createElement('textarea');
    textarea.value = value;
    textarea.setAttribute('readonly', '');
    textarea.style.position = 'fixed';
    textarea.style.left = '-9999px';
    document.body.appendChild(textarea);
    textarea.select();
    const copied = document.execCommand('copy');
    document.body.removeChild(textarea);
    return copied;
  } catch (error) {
    return false;
  }
};

const getFocusableElements = (root) => {
  if (!root) {
    return [];
  }

  return [...root.querySelectorAll('button:not([disabled]), a[href], [tabindex]:not([tabindex="-1"])')];
};

export function SpinWheelPopup({
  endpoint = '',
  ticketUrl = 'https://bilete.codrufestival.ro',
  segments = DEFAULT_SEGMENTS,
  storageKeys = {},
  copy = {},
}) {
  const labels = { ...DEFAULT_COPY, ...copy };
  const resultKey = storageKeys.result || 'codru_spin_result';
  const dismissedKey = storageKeys.dismissed || 'codrufestival_spin_wheel_dismissed_v1';
  const wheelSegments = Array.isArray(segments) && segments.length ? segments : DEFAULT_SEGMENTS;
  const titleId = useId();
  const errorId = useId();

  const [isOpen, setIsOpen] = useState(false);
  const [isSpinning, setIsSpinning] = useState(false);
  const [showResult, setShowResult] = useState(false);
  const [result, setResult] = useState(null);
  const [rotation, setRotation] = useState(0);
  const [animateWheel, setAnimateWheel] = useState(true);
  const [error, setError] = useState('');
  const [copied, setCopied] = useState(false);
  const [copyFailed, setCopyFailed] = useState(false);

  const overlayRef = useRef(null);
  const closeButtonRef = useRef(null);
  const copyResetRef = useRef(0);
  const spinTimeoutRef = useRef(0);

  const segmentGeometry = useMemo(
    () =>
      wheelSegments.map((segment, index) => {
        const midAngle = index * (360 / wheelSegments.length);
        const [labelX, labelY] = polarToXY(midAngle, WHEEL_RADIUS * 0.62);

        return {
          ...segment,
          index,
          path: segmentPath(index, wheelSegments.length),
          midAngle,
          labelX,
          labelY,
        };
      }),
    [wheelSegments],
  );

  useEffect(() => {
    let cancelled = false;

    if (hasBlockingStorage(resultKey, dismissedKey)) {
      return undefined;
    }

    waitForCookiebot().then(() => {
      if (!cancelled && !hasBlockingStorage(resultKey, dismissedKey)) {
        setIsOpen(true);
      }
    });

    return () => {
      cancelled = true;
    };
  }, [dismissedKey, resultKey]);

  const closePopup = useCallback(() => {
    if (isSpinning) {
      return;
    }

    writeStorage(dismissedKey, '1');
    setIsOpen(false);
  }, [dismissedKey, isSpinning]);

  useEffect(() => {
    if (!isOpen) {
      return undefined;
    }

    const previousOverflow = document.body.style.overflow;
    document.body.classList.add('codru-spin-wheel-open');
    document.body.style.overflow = 'hidden';
    closeButtonRef.current?.focus();

    const handleKeyDown = (event) => {
      if (event.key === 'Escape' && !isSpinning) {
        event.preventDefault();
        closePopup();
        return;
      }

      if (event.key !== 'Tab') {
        return;
      }

      const focusable = getFocusableElements(overlayRef.current);

      if (!focusable.length) {
        return;
      }

      const first = focusable[0];
      const last = focusable[focusable.length - 1];

      if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
      } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
      }
    };

    document.addEventListener('keydown', handleKeyDown);

    return () => {
      document.body.classList.remove('codru-spin-wheel-open');
      document.body.style.overflow = previousOverflow;
      document.removeEventListener('keydown', handleKeyDown);
    };
  }, [closePopup, isOpen, isSpinning, showResult]);

  useEffect(() => {
    return () => {
      window.clearTimeout(copyResetRef.current);
      window.clearTimeout(spinTimeoutRef.current);
    };
  }, []);

  const handleSpin = async () => {
    if (isSpinning || result || !endpoint) {
      return;
    }

    setError('');
    setIsSpinning(true);

    try {
      const prize = await requestSpin(endpoint);
      persistResult(resultKey, dismissedKey, prize);
      setResult(prize);

      const target = wheelTarget(prize.index, wheelSegments.length);

      if (prefersReducedMotion()) {
        setAnimateWheel(false);
        setRotation(target);
        setShowResult(true);
        setIsSpinning(false);
        return;
      }

      setAnimateWheel(true);
      setRotation(SPIN_TURNS * 360 + target);
      spinTimeoutRef.current = window.setTimeout(() => {
        setShowResult(true);
        setIsSpinning(false);
      }, SPIN_DURATION_MS);
    } catch (requestError) {
      setError(labels.errorLabel);
      setIsSpinning(false);
    }
  };

  const handleCopy = async () => {
    if (!result?.code) {
      return;
    }

    const didCopy = await copyText(result.code);
    window.clearTimeout(copyResetRef.current);

    if (didCopy) {
      setCopied(true);
      setCopyFailed(false);
      copyResetRef.current = window.setTimeout(() => setCopied(false), 1800);
      return;
    }

    setCopied(false);
    setCopyFailed(true);
    copyResetRef.current = window.setTimeout(() => setCopyFailed(false), 1800);
  };

  if (!isOpen || typeof document === 'undefined') {
    return null;
  }

  const spinLabel = isSpinning ? labels.spinningLabel : labels.spinLabel;
  const copyButtonLabel = copyFailed ? labels.copyFailedLabel : copied ? labels.copiedLabel : labels.copyLabel;

  return createPortal(
    <div
      ref={overlayRef}
      className="codru-spin-wheel"
      role="dialog"
      aria-modal="true"
      aria-labelledby={titleId}
      aria-describedby={error ? errorId : undefined}
    >
      <div className="codru-spin-wheel__panel">
        <div className="codru-spin-wheel__beam" aria-hidden="true" />
        <div className="codru-spin-wheel__forest" aria-hidden="true" />

        <button
          ref={closeButtonRef}
          type="button"
          className="codru-spin-wheel__close"
          aria-label={labels.closeLabel}
          onClick={closePopup}
          disabled={isSpinning}
        >
          <span aria-hidden="true">&times;</span>
        </button>

        <div className="codru-spin-wheel__wrap">
          <img className="codru-spin-wheel__wordmark" src={wordmark} alt="CODRU Festival" />
          <div className="codru-spin-wheel__meta">
            {labels.dates ? <p className="codru-spin-wheel__badge">{labels.dates}</p> : null}
            {labels.location ? <p className="codru-spin-wheel__badge">{labels.location}</p> : null}
          </div>
          <p className="codru-spin-wheel__eyebrow">{labels.eyebrow}</p>
          <h2 className="codru-spin-wheel__title" id={titleId}>
            {labels.titleBefore}
            <span className="codru-spin-wheel__accent">{labels.titleAccent}</span>
          </h2>
          <p className="codru-spin-wheel__lede">{labels.lede}</p>

          <div className="codru-spin-wheel__stage">
            <div className="codru-spin-wheel__pointer" aria-hidden="true" />
            <div className="codru-spin-wheel__glow" aria-hidden="true" />
            <div
              className={`codru-spin-wheel__wheel${animateWheel ? '' : ' is-instant'}`}
              style={{ transform: `rotate(${rotation}deg)` }}
            >
              <svg viewBox="0 0 300 300" aria-hidden="true">
                <g>
                  {segmentGeometry.map((segment) => (
                    <g key={`segment-${segment.index}`}>
                      <path d={segment.path} fill={segment.hex} stroke="#04140a" strokeWidth="2" />
                      <text
                        className="codru-spin-wheel__seg-label"
                        x={segment.labelX}
                        y={segment.labelY}
                        textAnchor="middle"
                        dominantBaseline="middle"
                        fontSize="26"
                        transform={`rotate(${segment.midAngle}, ${segment.labelX}, ${segment.labelY})`}
                      >
                        {segment.pct}%
                      </text>
                      <text
                        x={segment.labelX}
                        y={segment.labelY + 16}
                        textAnchor="middle"
                        dominantBaseline="middle"
                        fontSize="9"
                        fontWeight="700"
                        fill="#071466"
                        opacity="0.75"
                        transform={`rotate(${segment.midAngle}, ${segment.labelX}, ${segment.labelY})`}
                      >
                        {labels.offLabel}
                      </text>
                    </g>
                  ))}
                </g>
              </svg>
            </div>
            <div className="codru-spin-wheel__hub">
              <img src={wordmark} alt="" />
            </div>
          </div>

          <button
            type="button"
            className="codru-spin-wheel__spin"
            onClick={handleSpin}
            disabled={isSpinning || Boolean(result)}
            aria-busy={isSpinning}
          >
            {spinLabel}
          </button>
          {error ? (
            <p className="codru-spin-wheel__error" id={errorId} role="alert">
              {error}
            </p>
          ) : null}
          <p className="codru-spin-wheel__under">{labels.underWheel}</p>
        </div>

        {showResult && result ? (
          <div className="codru-spin-wheel__result is-visible">
            <div className="codru-spin-wheel__result-card">
              <p className="codru-spin-wheel__result-badge">{labels.resultBadge}</p>
              <p className="codru-spin-wheel__result-pct">
                {result.pct}% {labels.offLabel}
              </p>
              <div className="codru-spin-wheel__code-box">
                <p className="codru-spin-wheel__code-label">{labels.codeLabel}</p>
                <p className="codru-spin-wheel__code-value">{result.code}</p>
                <button
                  type="button"
                  className={`codru-spin-wheel__copy${copied ? ' is-copied' : ''}${copyFailed ? ' is-failed' : ''}`}
                  onClick={handleCopy}
                >
                  {copyButtonLabel}
                </button>
              </div>
              <p className="codru-spin-wheel__validity">{labels.validity}</p>
              <a className="codru-spin-wheel__cta" href={ticketUrl} target="_blank" rel="noopener noreferrer">
                {labels.ctaLabel}
              </a>
            </div>
          </div>
        ) : null}
      </div>
    </div>,
    document.body,
  );
}
