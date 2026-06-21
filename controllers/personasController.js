const personasModel = require('../models/personasModel');

/* ==========================
   GET
========================== */

exports.obtener = (req, res) => {

    personasModel.obtener(
        req.query,
        (error, result) => {

            if (error) {
                console.log(error);
                return res.status(500).json(error);
            }

            res.json(result[0]);
        }
    );
};

/* ==========================
   POST
========================== */

exports.insertar = (req, res) => {

    personasModel.insertar(
        req.body,
        (error, result) => {

            if (error) {
                console.log(error);
                return res.status(500).json(error);
            }

            res.json({
                mensaje: 'Registro insertado correctamente'
            });
        }
    );
};

/* ==========================
   PUT
========================== */

exports.actualizar = (req, res) => {

    personasModel.actualizar(
        req.body,
        (error, result) => {

            if (error) {
                console.log(error);
                return res.status(500).json(error);
            }

            res.json({
                mensaje: 'Registro actualizado correctamente'
            });
        }
    );
};