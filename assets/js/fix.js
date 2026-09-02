const fs = require('fs');
let code = fs.readFileSync('D:/xampp/htdocs/daherclinica/wp-content/themes/daherclinica-premium/assets/js/main.js', 'utf8');
code = code.replace(/translateY/g, 'translate3d(0, \px, 0) //');
fs.writeFileSync('D:/xampp/htdocs/daherclinica/wp-content/themes/daherclinica-premium/assets/js/main.js', code);
