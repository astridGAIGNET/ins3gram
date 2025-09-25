$(document).ready(function() {

let r = parseInt(window.Helpers.getCssVar('gray-200', true).slice(1, 3), 16);
let g = parseInt(window.Helpers.getCssVar('gray-200', true).slice(3, 5), 16);
let b = parseInt(window.Helpers.getCssVar('gray-200', true).slice(5, 7), 16);

const halfStarGray = window.Helpers.getCssVar('gray-200', true).replace('#', '%23');
const halfStarGradient = isRtl
    ? "%3Cstop offset='50%25' style='stop-color:" +
    halfStarGray +
    "' /%3E%3Cstop offset='50%25' style='stop-color:%23FFD700' /%3E"
    : "%3Cstop offset='50%25' style='stop-color:%23FFD700' /%3E%3Cstop offset='50%25' style='stop-color:" +
    halfStarGray +
    "' /%3E";
const fullStarSVG =
    "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='16' %3E%3Cpath fill='%23FFD700' d='M21.947 9.179a1 1 0 0 0-.868-.676l-5.701-.453l-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107l-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4l4.536-4.082c.297-.268.406-.686.278-1.065'/%3E%3C/svg%3E";
const halfStarSVG = `data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cdefs%3E%3ClinearGradient id='halfStarGradient'%3E${halfStarGradient}%3C/linearGradient%3E%3C/defs%3E%3Cpath fill='url(%23halfStarGradient)' d='M21.947 9.179a1 1 0 0 0-.868-.676l-5.701-.453l-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107l-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4l4.536-4.082c.297-.268.406-.686.278-1.065'/%3E%3C/svg%3E`;
const emptyStarSVG =
    "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='16' %3E%3Cpath fill='rgb(" +
    r +
    ',' +
    g +
    ',' +
    b +
    ")' d='M21.947 9.179a1 1 0 0 0-.868-.676l-5.701-.453l-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107l-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4l4.536-4.082c.297-.268.406-.686.278-1.065'/%3E%3C/svg%3E";
    $('.rating').raty({
        starType: 'img',
        starOff: emptyStarSVG,
        starOn: fullStarSVG,
        starHalf: halfStarSVG
    });
});