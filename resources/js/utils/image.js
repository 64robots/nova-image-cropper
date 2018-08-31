export const calculateAspectRatioFit = (
  srcWidth,
  srcHeight,
  maxWidth = 2000,
  maxHeight = 1000
) => {
  let ratio = 1;

  if (srcWidth > maxWidth || srcHeight > maxHeight) {
    ratio = Math.min(maxWidth / srcWidth, maxHeight / srcHeight);
  }

  return { width: srcWidth * ratio, height: srcHeight * ratio };
};

export const resizeImage = (image, type, cb) => {
  const newImage = new Image();
  newImage.onload = () => {
    const { width, height } = calculateAspectRatioFit(
      newImage.width,
      newImage.height
    );
    const canvas = document.createElement('canvas');
    canvas.width = width;
    canvas.height = height;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(newImage, 0, 0, width, height);
    const dataUrl = canvas.toDataURL(type);
    canvas.toBlob(blob => {
      const file = new File([blob], 'uploaded_file.jpg', {
        type,
        lastModified: Date.now()
      });
      const params = { dataUrl, width, height, file };
      cb(params);
    });
  };
  newImage.src = image;
};

export const UrlToBase64 = (url, onSuccess, onError) => {
  var img = new Image();
  var canvas = document.createElement('CANVAS');
  var ctx = canvas.getContext('2d');
  img.crossOrigin = '';

  img.onload = function() {
    canvas.height = img.height;
    canvas.width = img.width;
    ctx.drawImage(img, 0, 0);
    cb(canvas.toDataURL('image/png'));
    canvas = null;
  };
  img.src = url;
};
