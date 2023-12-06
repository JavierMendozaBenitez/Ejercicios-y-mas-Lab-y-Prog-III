"use strict";
const express = require('express');
const app = express();
app.set('puerto', 2023);
app.get('/', (request, response) => {
    response.send('GET - servidor NodeJS');
});
const fs = require('fs');
app.use(express.json());
const jwt = require("jsonwebtoken");
app.set("key", "mendoza.javier");
app.use(express.urlencoded({ extended: false }));
const multer = require('multer');
const mime = require('mime-types');
const storage = multer.diskStorage({
    destination: "public/juguetes/fotos/",
});
const upload = multer({
    storage: storage
});
const cors = require("cors");
app.use(cors());
app.use(express.static("public"));
const mysql = require('mysql');
const myconn = require('express-myconnection');
const db_options = {
    host: 'localhost',
    port: 3306,
    user: 'root',
    password: '',
    database: 'jugueteria_bd'
};
app.use(myconn(mysql, db_options, 'single'));
const verificar_usuario = express.Router();
const verificar_jwt = express.Router();
const alta_baja = express.Router();
const modificar = express.Router();
verificar_usuario.use((request, response, next) => {
    let obj = request.body;
    request.getConnection((err, conn) => {
        if (err)
            throw ("Error al conectarse a la base de datos.");
        conn.query("SELECT * FROM usuarios WHERE correo = ? and clave = ?  ", [obj.correo, obj.clave], (err, rows) => {
            if (err)
                throw ("Error en consulta de base de datos.");
            if (rows.length == 1) {
                response.obj_usuario = rows[0];
                next();
            }
            else {
                response.status(403).json({
                    exito: false,
                    mensaje: "Correo y/o clave incorrectos.",
                    jwt: null
                });
            }
        });
    });
});
app.post("/login", verificar_usuario, (request, response, obj) => {
    const user = response.obj_usuario;
    const payload = {
        usuario: {
            id: user.id,
            correo: user.correo,
            nombre: user.nombre,
            apellido: user.apellido,
            foto: user.foto,
            perfil: user.perfil
        },
        alumno: "Mendoza Javier",
        dni_alumno: "36727747"
    };
    const token = jwt.sign(payload, app.get("key"), {
        expiresIn: "2m"
    });
    response.json({
        exito: true,
        mensaje: "JWT creado!!",
        jwt: token
    });
});
verificar_jwt.use((request, response, next) => {
    let token = request.headers["x-access-token"] || request.headers["authorization"];
    if (!token) {
        response.status(401).send({
            error: "El JWT es requerido!!!"
        });
        return;
    }
    if (token.startsWith("Bearer ")) {
        token = token.slice(7, token.length);
    }
    if (token) {
        jwt.verify(token, app.get("key"), (error, decoded) => {
            if (error) {
                return response.json({
                    exito: false,
                    mensaje: "El JWT NO es válido!!!",
                    status: 403
                });
            }
            else {
                response.jwt = decoded;
                next();
            }
        });
    }
});
app.get('/login', verificar_jwt, (request, response) => {
    response.json({ exito: true, string: "Logueo exitoso!", jwt: response.jwt, status: 200 });
});
app.post("/agregarJugueteBD", upload.single("foto"), verificar_jwt, (request, response) => {
    let obj_retorno = {
        exito: false,
        mensaje: "No se pudo agregar el Juguete a la BD",
    };
    let file = request.file;
    let extension = mime.extension(file.mimetype);
    let juguete_obj = JSON.parse(request.body.juguete_json);
    let path = file.destination + juguete_obj.marca + "." + extension;
    console.log(path);
    juguete_obj.path_foto = path.split("public/")[1];
    request.getConnection((err, conn) => {
        if (err)
            throw "Error al conectarse a la base de datos.";
        conn.query("INSERT INTO juguetes set ?", [juguete_obj], (err, rows) => {
            if (err) {
                console.log(err);
                throw "Error en consulta de base de datos.";
            }
            obj_retorno.exito = true;
            obj_retorno.mensaje = "Se pudo agregar correctamente el Juguete";
            fs.renameSync(file.path, path);
            response.json(obj_retorno);
        });
    });
});
app.get("/listarJuguetesBD", verificar_jwt, (request, response) => {
    let obj_retorno = {
        exito: false,
        mensaje: "No se encuentran juguetes en la BD",
        dato: {},
        status: 424,
    };
    request.getConnection((err, conn) => {
        if (err)
            throw "Error al conectarse a la base de datos.";
        conn.query("SELECT * FROM juguetes", (err, rows) => {
            if (err)
                throw "Error en consulta de base de datos.";
            if (rows.length == 0) {
                response.status(obj_retorno.status).json(obj_retorno);
            }
            else {
                obj_retorno.exito = true;
                obj_retorno.mensaje = "Listado de Juguetes";
                obj_retorno.dato = rows;
                obj_retorno.status = 200;
                response.status(obj_retorno.status).json(obj_retorno);
            }
        });
    });
});
app.delete("/toys", verificar_jwt, (request, response) => {
    let obj_retorno = {
        exito: false,
        mensaje: "No se pudo eliminar el Juguete a la BD",
        status: 418,
    };
    let id_juguete = JSON.parse(request.body.id_juguete);
    let path_foto = "public/";
    request.getConnection((err, conn) => {
        if (err)
            throw "Error al conectarse a la base de datos.";
        conn.query("SELECT path_foto FROM juguetes WHERE id = ?", [id_juguete], (err, result) => {
            if (err)
                throw "Error en consulta de base de datos.";
            if (result.length != 0) {
                path_foto += result[0].path_foto;
            }
        });
    });
    request.getConnection((err, conn) => {
        if (err)
            throw "Error al conectarse a la base de datos.";
        conn.query("DELETE FROM juguetes WHERE id = ?", [id_juguete], (err, rows) => {
            if (err) {
                console.log(err);
                throw "Error en consulta de base de datos.";
            }
            if (fs.existsSync(path_foto) && path_foto != "public/") {
                fs.unlink(path_foto, (err) => {
                    if (err)
                        throw err;
                    console.log(path_foto + " fue borrado.");
                });
            }
            if (rows.affectedRows == 0) {
                response.status(obj_retorno.status).json(obj_retorno);
            }
            else {
                obj_retorno.exito = true;
                obj_retorno.mensaje = "Juguete eliminado correctamente!";
                obj_retorno.status = 200;
                response.status(obj_retorno.status).json(obj_retorno);
            }
        });
    });
});
app.post("/toys", upload.single("foto"), verificar_jwt, (request, response) => {
    let obj_retorno = {
        exito: false,
        mensaje: "No se pudo modificar el Juguete a la BD",
        status: 418,
    };
    let file = request.file;
    let extension = mime.extension(file.mimetype);
    let juguete_obj = JSON.parse(request.body.juguete);
    let path = file.destination + juguete_obj.marca + "_modificacion." + extension;
    juguete_obj.path = path.split("public/")[1];
    let obj_modif = {};
    obj_modif.marca = juguete_obj.marca;
    obj_modif.precio = juguete_obj.precio;
    obj_modif.path_foto = juguete_obj.path;
    request.getConnection((err, conn) => {
        if (err)
            throw ("Error al conectarse a la base de datos.");
        conn.query("UPDATE juguetes SET ? WHERE id = ?", [obj_modif, juguete_obj.id_juguete], (err, rows) => {
            if (err) {
                console.log(err);
                throw ("Error en consulta de base de datos.");
            }
            if (rows.affectedRows > 0) {
                fs.renameSync(file.path, path);
                obj_retorno.status = 200;
                obj_retorno.exito = true;
                obj_retorno.mensaje = "El juguete fue modificado correctamente";
            }
            response.status(obj_retorno.status).json(obj_retorno);
        });
    });
});
app.listen(app.get('puerto'), () => {
    console.log('Servidor corriendo sobre puerto:', app.get('puerto'));
});
//# sourceMappingURL=servidor_node.js.map