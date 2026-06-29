// modelo del modulo de inventario
const model = require('../models/inventarioModel');

/* ==========================
   ALMACENES
========================== */

const getAllAlmacenes = (req, res) => {
  model.getAllAlmacenes((err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result);
  });
};

const insAlmacen = (req, res) => {
  model.insAlmacen(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(201).json({ mensaje: 'almacen creado', resultado: result });
  });
};

const updAlmacen = (req, res) => {
  const datos = { ...req.body, cod_almacen: req.params.id };
  model.updAlmacen(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'almacen actualizado', resultado: result });
  });
};

const delAlmacen = (req, res) => {
  model.softDeleteAlmacen(req.params.id, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'almacen eliminado correctamente' });
  });
};

/* ==========================
   CATEGORIAS
========================== */

const getAllCategorias = (req, res) => {
  model.getAllCategorias((err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result);
  });
};

const insCategoria = (req, res) => {
  model.insCategoria(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(201).json({ mensaje: 'categoria creada', resultado: result });
  });
};

const updCategoria = (req, res) => {
  const datos = { ...req.body, cod_categoria: req.params.id };
  model.updCategoria(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'categoria actualizada', resultado: result });
  });
};

const delCategoria = (req, res) => {
  model.softDeleteCategoria(req.params.id, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'categoria eliminada correctamente' });
  });
};

/* ==========================
   ITEMS
========================== */

const getAllItems = (req, res) => {
  model.getAllItems((err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result);
  });
};

const insItem = (req, res) => {
  model.insItem(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(201).json({ mensaje: 'item creado', resultado: result });
  });
};

const updItem = (req, res) => {
  const datos = { ...req.body, cod_item: req.params.id };
  model.updItem(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'item actualizado', resultado: result });
  });
};

const delItem = (req, res) => {
  model.softDeleteItem(req.params.id, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'item eliminado correctamente' });
  });
};

/* ==========================
   ASIGNACIONES
========================== */

const getAllAsignaciones = (req, res) => {
  model.getAllAsignaciones((err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result);
  });
};

const insAsignacion = (req, res) => {
  model.insAsignacion(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(201).json({ mensaje: 'asignacion creada', resultado: result });
  });
};

const updAsignacion = (req, res) => {
  const datos = { ...req.body, cod_asignacion: req.params.id };
  model.updAsignacion(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'asignacion actualizada', resultado: result });
  });
};

const delAsignacion = (req, res) => {
  model.softDeleteAsignacion(req.params.id, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'asignacion eliminada correctamente' });
  });
};

/* ==========================
   RESERVAS
========================== */

const getAllReservas = (req, res) => {
  model.getAllReservas((err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result);
  });
};

const insReserva = (req, res) => {
  model.insReserva(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(201).json({ mensaje: 'reserva de inventario creada', resultado: result });
  });
};

const updReserva = (req, res) => {
  const datos = { ...req.body, cod_reserva: req.params.id };
  model.updReserva(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'reserva de inventario actualizada', resultado: result });
  });
};

const delReserva = (req, res) => {
  model.softDeleteReserva(req.params.id, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'reserva eliminada correctamente' });
  });
};

module.exports = {
  getAllAlmacenes, insAlmacen, updAlmacen, delAlmacen,
  getAllCategorias, insCategoria, updCategoria, delCategoria,
  getAllItems, insItem, updItem, delItem,
  getAllAsignaciones, insAsignacion, updAsignacion, delAsignacion,
  getAllReservas, insReserva, updReserva, delReserva
};
