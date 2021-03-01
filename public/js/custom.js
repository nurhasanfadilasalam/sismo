/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

function convertToRp(angka) {
    let rupiah = '';
    const angkarev = angka ? angka.toString().split('').reverse().join('') : 0;

    for (let i = 0; i < angkarev.length; i++) {
    if (i % 3 === 0) rupiah += `${angkarev.substr(i, 3)}.`;
    }

    return (angka > 0) ? `${rupiah.split('', rupiah.length - 1).reverse().join('')}` : 0;
};