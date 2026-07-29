const db = require('../config/db');
 
// Busca un usuario por su nombre (incluye CLAVE)
const login = (nombreUsr, callback) => {
    db.query(
        'CALL SP_AUTH_LOGIN(?)',
        [nombreUsr],
        callback
    );
};
 
// Verifica si un nombre de usuario ya existe
const validarNombreUsr = (nombreUsr, callback) => {
    db.query(
        'CALL SP_AUTH_VALIDAR_NOMBRE_USR(?)',
        [nombreUsr],
        callback
    );
};
 
// Registra un nuevo usuario para una persona ya existente
const registrarUsuario = (datos, callback) => {
    const { codPersona, codTipoUsr, nombreUsr, clave, token, usrIngreso } = datos;
    db.query(
        'CALL SP_AUTH_REGISTRAR_USUARIO(?, ?, ?, ?, ?, ?)',
        [codPersona, codTipoUsr, nombreUsr, clave, token || null, usrIngreso],
        callback
    );
};
 
// Cambia clave (y opcionalmente token) de un usuario existente
const cambiarClave = (datos, callback) => {
    const { codUsuario, claveNueva, tokenNuevo, usrIngreso } = datos;
    db.query(
        'CALL SP_AUTH_CAMBIAR_CLAVE(?, ?, ?, ?)',
        [codUsuario, claveNueva, tokenNuevo || null, usrIngreso],
        callback
    );
};
 
// Trae datos de usuario por ID
const selectUsuarioById = (codUsuario, callback) => {
    db.query(
        'CALL SP_AUTH_SELECT_USUARIO_BY_ID(?)',
        [codUsuario],
        callback
    );
};
 
module.exports = {
    login,
    validarNombreUsr,
    registrarUsuario,
    cambiarClave,
    selectUsuarioById
};