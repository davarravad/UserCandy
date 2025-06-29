const express = require('express');
const app = express();
const port = process.env.PORT || 3000;

app.use(express.static('public'));

app.get('/api/hello', (req, res) => {
  res.json({ message: 'Hello from Node!' });
});

app.listen(port, () => {
  console.log(`Node server running on port ${port}`);
});
