const authModel = require('../models/authModel');
 
// POST /api/auth/login
function login(req, res) {
    const { nombreUsr, clave } = req.body;
 
    if (!nombreUsr || !clave) {
        return res.status(400).json({
            ok: false,
            mensaje: 'nombreUsr y clave son obligatorios'
        });
    }
 
    authModel.login(nombreUsr, (err, rows) => {
        if (err) {
            console.error('Error en login:', err);
            return res.status(500).json({ ok: false, mensaje: 'Error interno del servidor' });
        }
 
        const usuario = rows[0] && rows[0][0] ? rows[0][0] : null;
 
        if (!usuario) {
            return res.status(404).json({
                ok: false,
                mensaje: 'Usuario no encontrado'
            });
        }
 
        if (usuario.IND_USR !== '1') {
            return res.status(403).json({
                ok: false,
                mensaje: 'Usuario inactivo, contacte al administrador'
            });
        }
 
        if (usuario.CLAVE !== clave) {
            return res.status(401).json({
                ok: false,
                mensaje: 'Credenciales invalidas'
            });
        }
 
        const { CLAVE, ...usuarioSeguro } = usuario;
 
        return res.json({
            ok: true,
            requiereCambioClave: usuario.IND_PRIMER_ING === '1',
            usuario: usuarioSeguro
        });
    });
}
 
// GET /api/auth/validar-nombre/:nombreUsr
function validarNombreUsr(req, res) {
    const { nombreUsr } = req.params;
 
    authModel.validarNombreUsr(nombreUsr, (err, rows) => {
        if (err) {
            console.error('Error validando nombre de usuario:', err);
            return res.status(500).json({ ok: false, mensaje: 'Error interno del servidor' });
        }
 
        const existe = rows[0] && rows[0][0] ? rows[0][0].EXISTE > 0 : false;
        return res.json({ ok: true, existe });
    });
}
 
// POST /api/auth/register
function register(req, res) {
    const { codPersona, codTipoUsr, nombreUsr, clave, token, usrIngreso } = req.body;
 
    if (!codPersona || !codTipoUsr || !nombreUsr || !clave || !usrIngreso) {
        return res.status(400).json({
            ok: false,
            mensaje: 'codPersona, codTipoUsr, nombreUsr, clave y usrIngreso son obligatorios'
        });
    }
 
    // Primero validamos si existe
    authModel.validarNombreUsr(nombreUsr, (err, rowsVal) => {
        if (err) {
            console.error('Error en register (validar):', err);
            return res.status(500).json({ ok: false, mensaje: 'Error interno del servidor' });
        }
 
        const existe = rowsVal[0] && rowsVal[0][0] ? rowsVal[0][0].EXISTE > 0 : false;
        if (existe) {
            return res.status(409).json({
                ok: false,
                mensaje: 'El nombre de usuario ya existe'
            });
        }
 
        // Si no existe, procedemos a registrar
        authModel.registrarUsuario({ codPersona, codTipoUsr, nombreUsr, clave, token, usrIngreso }, (errReg, rowsReg) => {
            if (errReg) {
                console.error('Error en register (insertar):', errReg);
                return res.status(500).json({ ok: false, mensaje: 'Error interno del servidor' });
            }
 
            const resultado = rowsReg[0] && rowsReg[0][0] ? rowsReg[0][0] : null;
 
            return res.status(201).json({
                ok: true,
                mensaje: 'Usuario creado correctamente',
                data: resultado
            });
        });
    });
}
 
// PUT /api/auth/change-password
function changePassword(req, res) {
    const { codUsuario, claveNueva, tokenNuevo, usrIngreso } = req.body;
 
    if (!codUsuario || !claveNueva || !usrIngreso) {
        return res.status(400).json({
            ok: false,
            mensaje: 'codUsuario, claveNueva y usrIngreso son obligatorios'
        });
    }
 
    authModel.cambiarClave({ codUsuario, claveNueva, tokenNuevo, usrIngreso }, (err) => {
        if (err) {
            console.error('Error en changePassword:', err);
            return res.status(500).json({ ok: false, mensaje: 'Error interno del servidor' });
        }
 
        return res.json({
            ok: true,
            mensaje: 'Contraseña actualizada correctamente'
        });
    });
}
 
// GET /api/auth/usuario/:codUsuario
function getUsuario(req, res) {
    const { codUsuario } = req.params;
 
    authModel.selectUsuarioById(codUsuario, (err, rows) => {
        if (err) {
            console.error('Error en getUsuario:', err);
            return res.status(500).json({ ok: false, mensaje: 'Error interno del servidor' });
        }
 
        const usuario = rows[0] && rows[0][0] ? rows[0][0] : null;
 
        if (!usuario) {
            return res.status(404).json({ ok: false, mensaje: 'Usuario no encontrado' });
        }
 
        return res.json({ ok: true, usuario });
    });
}
 
module.exports = {
    login,
    validarNombreUsr,
    register,
    changePassword,
    getUsuario
};