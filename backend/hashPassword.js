const bcrypt = require('bcrypt');

async function createHash(password) {
  const saltRounds = 10;
  const hash = await bcrypt.hash(password, saltRounds);
  console.log('Hashed password:', hash);
}

createHash('123456');
