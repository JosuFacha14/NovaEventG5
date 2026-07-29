const express = require('express');
const router = express.Router();
const ctrl = require('../controllers/authController');

//rutas para el login, registro y validación de nombre de usuario


router.post('/login', ctrl.login);
router.get('/validar-nombre/:nombreUsr', ctrl.validarNombreUsr);
router.post('/register', ctrl.register);
router.put('/change-password', ctrl.changePassword);
router.get('/usuario/:codUsuario', ctrl.getUsuario);
 
module.exports = router;
 
