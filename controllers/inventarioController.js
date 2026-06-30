const model = require('../models/inventarioModel');
 
/* ================================================================
   VALIDACIONES HELPERS
================================================================ */
const camposRequeridos = (body, campos) => {
  return campos.filter(c => body[c] === undefined || body[c] === null || body[c] === '');
};
 
/* ================================================================
   GET  →  SEL_ALL_INVENTARIO
   Query params opcionales: cod_item | cod_categoria | cod_almacen | cod_evento
   Sin params → retorna todos los ítems activos
================================================================ */
const getInventario = (req, res) => {
  const { cod_item, cod_categoria, cod_almacen, cod_evento } = req.query;
 
  // Determina qué rama del SP ejecutar
  if (cod_item) {
    return model.getItemById(cod_item, (err, result) => {
      if (err) return res.status(500).json({ error: err.message });
      if (!result || result.length === 0)
        return res.status(404).json({ mensaje: 'Item no encontrado' });
      res.status(200).json(result);
    });
  }
 
  if (cod_categoria) {
    return model.getItemsByCategoria(cod_categoria, (err, result) => {
      if (err) return res.status(500).json({ error: err.message });
      res.status(200).json(result);
    });
  }
 
  if (cod_almacen) {
    return model.getItemsByAlmacen(cod_almacen, (err, result) => {
      if (err) return res.status(500).json({ error: err.message });
      res.status(200).json(result);
    });
  }
 
  if (cod_evento) {
    return model.getItemsByEvento(cod_evento, (err, result) => {
      if (err) return res.status(500).json({ error: err.message });
      res.status(200).json(result);
    });
  }
 
  // Sin filtros → todos los ítems activos
  model.getAllItems((err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result);
  });
};
 
/* ================================================================
   POST  →  INSERT_INVENTARIO
   Body obligatorio: COD_ITEM, NOM_ITEM, DES_ITEM, CAN_TOTAL, USR_REGISTRO
   Body opcional:    datos de categoría, almacén, reserva, asignación
================================================================ */
const postInventario = (req, res) => {
  const faltantes = camposRequeridos(req.body, [
    'COD_ITEM', 'NOM_ITEM', 'DES_ITEM', 'CAN_TOTAL', 'USR_REGISTRO'
  ]);
 
  if (faltantes.length > 0) {
    return res.status(400).json({
      error: 'Campos obligatorios faltantes',
      campos: faltantes
    });
  }
 
  // Validación de reserva: si se envía reserva, sus campos son requeridos
  if (req.body.COD_RESERVA) {
    const faltantesRes = camposRequeridos(req.body, [
      'COD_EVENTO_RES', 'CAN_RESERVADA', 'FEC_INICIO_RESERVA', 'NOM_SOLICITANTE'
    ]);
    if (faltantesRes.length > 0) {
      return res.status(400).json({
        error: 'Campos obligatorios para reserva faltantes',
        campos: faltantesRes
      });
    }
  }
 
  // Validación de asignación: si se envía asignación, sus campos son requeridos
  if (req.body.COD_ASIGNACION) {
    const faltantesAsig = camposRequeridos(req.body, [
      'COD_EVENTO_ASIG', 'CAN_ASIGNADA', 'FEC_SALIDA', 'NOM_RESPONSABLE'
    ]);
    if (faltantesAsig.length > 0) {
      return res.status(400).json({
        error: 'Campos obligatorios para asignacion faltantes',
        campos: faltantesAsig
      });
    }
  }
 
  model.insertInventario(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(201).json({
      mensaje: 'Inventario registrado correctamente',
      resultado: result
    });
  });
};
 
/* ================================================================
   PUT  →  SP_IN_UPDATE
   Body obligatorio: USR_REGISTRO
   Body opcional:    ACCION (para soft delete) + campos a actualizar
 
   ACCION puede ser:
     null / omitido   → UPDATE normal (solo actualiza los campos enviados)
     'DEL_IN_ITEM'    → Soft delete de ítem  (requiere COD_ITEM)
     'DEL_IN_CATEGORIA' → Soft delete de categoría (requiere COD_CATEGORIA)
     'DEL_IN_ALMACEN' → Soft delete de almacén (requiere COD_ALMACEN)
================================================================ */
const putInventario = (req, res) => {
  const { ACCION, USR_REGISTRO, COD_ITEM, COD_CATEGORIA, COD_ALMACEN } = req.body;
 
  if (!USR_REGISTRO) {
    return res.status(400).json({ error: 'USR_REGISTRO es obligatorio' });
  }
 
  // Validaciones específicas por acción
  if (ACCION === 'DEL_IN_ITEM' && !COD_ITEM) {
    return res.status(400).json({ error: 'COD_ITEM es requerido para DEL_IN_ITEM' });
  }
  if (ACCION === 'DEL_IN_CATEGORIA' && !COD_CATEGORIA) {
    return res.status(400).json({ error: 'COD_CATEGORIA es requerido para DEL_IN_CATEGORIA' });
  }
  if (ACCION === 'DEL_IN_ALMACEN' && !COD_ALMACEN) {
    return res.status(400).json({ error: 'COD_ALMACEN es requerido para DEL_IN_ALMACEN' });
  }
 
  // Para UPDATE normal debe venir al menos un código de entidad a actualizar
  if (!ACCION && !COD_ITEM && !COD_CATEGORIA && !COD_ALMACEN &&
      !req.body.COD_RESERVA && !req.body.COD_ASIGNACION) {
    return res.status(400).json({
      error: 'Debe enviar al menos un COD (COD_ITEM, COD_CATEGORIA, COD_ALMACEN, COD_RESERVA o COD_ASIGNACION) para actualizar'
    });
  }
 
  model.updateInventario(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
 
    // Mensaje descriptivo según la acción ejecutada
    const mensajes = {
      DEL_IN_ITEM:       'Item desactivado correctamente (soft delete)',
      DEL_IN_CATEGORIA:  'Categoria desactivada correctamente (soft delete)',
      DEL_IN_ALMACEN:    'Almacen desactivado correctamente (soft delete)'
    };
 
    res.status(200).json({
      mensaje: mensajes[ACCION] || 'Inventario actualizado correctamente',
      resultado: result
    });
  });
};
 
module.exports = {
  getInventario,   // GET
  postInventario,  // POST
  putInventario    // PUT (update + soft delete)
};
 