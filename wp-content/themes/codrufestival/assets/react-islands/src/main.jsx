import React from 'react';
import { createRoot } from 'react-dom/client';
import { ArtistExpandableCards } from './components/ArtistExpandableCards.jsx';
import { CountdownBadge } from './components/CountdownBadge.jsx';
import { NewsletterSignupTeaser } from './components/NewsletterSignupTeaser.jsx';
import './styles.css';

const registry = {
  ArtistExpandableCards,
  CountdownBadge,
  NewsletterSignupTeaser,
};

const mountedRoots = new WeakMap();

const parseProps = (element) => {
  const rawProps = element.getAttribute('data-props');

  if (!rawProps) {
    return {};
  }

  try {
    return JSON.parse(rawProps);
  } catch (error) {
    console.error('[Codru React Islands] Invalid props JSON', element, error);
    return {};
  }
};

const mountIsland = (element) => {
  if (mountedRoots.has(element)) {
    return;
  }

  const componentName = element.getAttribute('data-react-island');
  const Component = registry[componentName];

  if (!Component) {
    console.warn(`[Codru React Islands] Component "${componentName}" is not registered.`);
    return;
  }

  const root = createRoot(element);
  mountedRoots.set(element, root);
  root.render(<Component {...parseProps(element)} />);
};

const mountReactIslands = (root = document) => {
  root.querySelectorAll('[data-react-island]').forEach(mountIsland);
};

const observeReactIslands = () => {
  const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      mutation.addedNodes.forEach((node) => {
        if (!(node instanceof HTMLElement)) {
          return;
        }

        if (node.matches('[data-react-island]')) {
          mountIsland(node);
        }

        mountReactIslands(node);
      });
    });
  });

  observer.observe(document.documentElement, {
    childList: true,
    subtree: true,
  });
};

window.CodruFestivalReactIslands = {
  mount: mountReactIslands,
  register(name, Component) {
    registry[name] = Component;
  },
  registry,
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    mountReactIslands();
    observeReactIslands();
  });
} else {
  mountReactIslands();
  observeReactIslands();
}
