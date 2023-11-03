const express = require("express")
const mysql = require("mysql2/promise")

const app = express()
const port = 3306

app.use(express.json())

const dbConfig = {
  host: "localhost",
  user: "root",
  password: "",
  database: "secure_software",
}

// Ruta para registrar un usuario
app.post("/registrar", async (req, res) => {
  try {
    const connection = await mysql.createConnection(dbConfig)
    const { email, username, password } = req.body

    // Realiza la inserción en la base de datos
    const [rows] = await connection.execute(
      "INSERT INTO tabla_de_usuarios (email, username, password) VALUES (?, ?, ?)",
      [email, username, password]
    )

    connection.end()
    res.json({ success: true, message: "Registro exitoso" })
  } catch (error) {
    res.status(500).json({ success: false, error: error.message })
  }
})

app.listen(port, () => {
  console.log(`Servidor escuchando en el puerto ${port}`)
})
