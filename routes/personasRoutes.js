const express = require('express');

const router = express.Router();

const personasController = require('../controllers/personasController');

/* ==========================
   GET
========================== */

router.get(
    '/personas',
    personasController.obtener
);

/* ==========================
   POST
========================== */

router.post(
    '/personas',
    personasController.insertar
);

/* ==========================
   PUT
========================== */

router.put(
    '/personas',
    personasController.actualizar
);

module.exports = router;