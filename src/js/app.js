import 'babel-polyfill';

const isPhone = () => {
  return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
};

// Check for modern browser

if ('querySelector' in document && 'addEventListener' in window) {
  document.documentElement.classList.remove('no-js');
  document.documentElement.classList.add('js');
}