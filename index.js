const express = require('express')
const path = require('path')
const PORT = process.env.PORT || 5000
var mysql = require('mysql');
connection = mysql.createConnection({
    host: "lwhawk.com",
    user: "lwhawkco_lhyltoncs",
    password: "Ll6677327"
});

connection.connect(function(err) {
    if (err) throw err;
    console.log("Connected to database");
})


app = express();

app.use(express.static(path.join(__dirname, 'public')));

app.get('/', (req, res) => res.sendFile('index.html'));













app.listen(PORT, () => console.log(`Listening on ${ PORT }`));

