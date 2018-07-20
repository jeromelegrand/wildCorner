require('jquery');
require('popper.js');
require('bootstrap');

const imagesContext = require.context('../images', true, /\.(png|jpg|jpeg|gif|ico|svg|webp)$/);
imagesContext.keys().forEach(imagesContext);