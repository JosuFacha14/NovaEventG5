-- =============================================================================
-- STORED PROCEDURES MÓDULO INVENTARIO (IN)
-- Cindy Michelle Osorto González - Joel Fernando Valladares Cruz
-- Generados para coincidir exactamente con inventarioModel.js
-- =============================================================================

-- ─────────────────────────────────────────────────────────────────────────────
-- 1. SEL_ALL_INVENTARIO  →  CALL SEL_ALL_INVENTARIO(cod_item, cod_categoria, cod_almacen, cod_evento)
-- ─────────────────────────────────────────────────────────────────────────────
DROP PROCEDURE IF EXISTS SEL_ALL_INVENTARIO;

DELIMITER $$
CREATE PROCEDURE SEL_ALL_INVENTARIO(
    IN pi_cod_item       BIGINT,
    IN pi_cod_categoria  BIGINT,
    IN pi_cod_almacen    BIGINT,
    IN pi_cod_evento     BIGINT
)
BEGIN
    -- Ítems con nombre de categoría y almacén
    SELECT
         i.COD_ITEM
        ,i.NOM_ITEM
        ,i.DES_ITEM
        ,i.COD_CATEGORIA
        ,c.NOM_CATEGORIA
        ,i.COD_ALMACEN
        ,a.NOM_ALMACEN
        ,i.CAN_TOTAL
        ,i.CAN_DISPONIBLE
        ,i.IND_ESTADO
        ,i.COD_ITEM_UNICO
        ,i.IMG_FOTO_URL
        ,i.FEC_ADQUISICION
        ,i.MON_COSTO
        ,i.USR_REGISTRO
        ,i.FEC_REGISTRO
    FROM IN_INVENTARIO_ITEM i
    LEFT JOIN IN_CATEGORIAS_INVENTARIO c ON i.COD_CATEGORIA = c.COD_CATEGORIA
    LEFT JOIN IN_ALMACENES             a ON i.COD_ALMACEN   = a.COD_ALMACEN
    WHERE (pi_cod_item      IS NULL OR i.COD_ITEM     = pi_cod_item)
    AND   (pi_cod_categoria IS NULL OR i.COD_CATEGORIA = pi_cod_categoria)
    AND   (pi_cod_almacen   IS NULL OR i.COD_ALMACEN   = pi_cod_almacen)
    AND   i.IND_ESTADO <> 'BAJA';

    -- Categorías activas
    SELECT
         COD_CATEGORIA
        ,NOM_CATEGORIA
        ,DES_CATEGORIA
        ,DES_ICONO
        ,IND_ACTIVA
        ,USR_REGISTRO
        ,FEC_REGISTRO
    FROM IN_CATEGORIAS_INVENTARIO
    WHERE IND_ACTIVA = TRUE
    AND   (pi_cod_categoria IS NULL OR COD_CATEGORIA = pi_cod_categoria);

    -- Almacenes activos
    SELECT
         COD_ALMACEN
        ,NOM_ALMACEN
        ,DIR_UBICACION
        ,COD_EMPLEADO
        ,CAN_CAPACIDAD
        ,IND_ACTIVO
        ,USR_REGISTRO
        ,FEC_REGISTRO
    FROM IN_ALMACENES
    WHERE IND_ACTIVO = TRUE
    AND   (pi_cod_almacen IS NULL OR COD_ALMACEN = pi_cod_almacen);

    -- Reservas de inventario
    SELECT
         r.COD_RESERVA
        ,r.COD_EVENTO
        ,r.COD_ITEM
        ,i.NOM_ITEM
        ,r.CAN_RESERVADA
        ,r.FEC_INICIO_RESERVA
        ,r.FEC_FIN_RESERVA
        ,r.IND_ESTADO_RESERVA
        ,r.NOM_SOLICITANTE
        ,r.DES_NOTAS
        ,r.USR_REGISTRO
        ,r.FEC_REGISTRO
    FROM IN_RESERVAS_INVENTARIO r
    LEFT JOIN IN_INVENTARIO_ITEM i ON r.COD_ITEM = i.COD_ITEM
    WHERE (pi_cod_evento IS NULL OR r.COD_EVENTO = pi_cod_evento)
    AND   (pi_cod_item   IS NULL OR r.COD_ITEM   = pi_cod_item);

    -- Asignaciones a evento
    SELECT
         ae.COD_ASIGNACION
        ,ae.COD_EVENTO
        ,ae.COD_ITEM
        ,i.NOM_ITEM
        ,ae.CAN_ASIGNADA
        ,ae.FEC_SALIDA
        ,ae.FEC_RETORNO
        ,ae.IND_ESTADO
        ,ae.IND_CONDICION
        ,ae.NOM_RESPONSABLE
        ,ae.DES_OBSERVACIONES
        ,ae.USR_REGISTRO
        ,ae.FEC_REGISTRO
    FROM IN_ASIGNACION_EVENTO ae
    LEFT JOIN IN_INVENTARIO_ITEM i ON ae.COD_ITEM = i.COD_ITEM
    WHERE (pi_cod_evento IS NULL OR ae.COD_EVENTO = pi_cod_evento)
    AND   (pi_cod_item   IS NULL OR ae.COD_ITEM   = pi_cod_item);

END$$
DELIMITER ;

-- ─────────────────────────────────────────────────────────────────────────────
-- 2. INSERT_INVENTARIO  →  33 parámetros según inventarioModel.js
-- ─────────────────────────────────────────────────────────────────────────────
DROP PROCEDURE IF EXISTS INSERT_INVENTARIO;

DELIMITER $$
CREATE PROCEDURE INSERT_INVENTARIO(
    -- Categoría
    IN pi_cod_categoria   BIGINT,
    IN pv_nom_categoria   VARCHAR(100),
    IN pv_des_categoria   TEXT,
    IN pv_des_icono       VARCHAR(50),
    -- Almacén
    IN pi_cod_almacen     BIGINT,
    IN pv_nom_almacen     VARCHAR(100),
    IN pv_dir_ubicacion   VARCHAR(200),
    IN pi_cod_empleado    BIGINT,
    IN pi_can_capacidad   INT,
    -- Ítem
    IN pi_cod_item        BIGINT,
    IN pv_nom_item        VARCHAR(150),
    IN pv_des_item        TEXT,
    IN pi_can_total       INT,
    IN pi_can_disponible  INT,
    IN pv_cod_item_unico  VARCHAR(50),
    IN pv_img_foto_url    VARCHAR(255),
    IN pd_fec_adquisicion DATE,
    IN pm_mon_costo       DECIMAL(10,2),
    -- Reserva
    IN pi_cod_reserva     BIGINT,
    IN pi_cod_evento_res  BIGINT,
    IN pi_can_reservada   INT,
    IN pdt_fec_inicio_res DATETIME,
    IN pdt_fec_fin_res    DATETIME,
    IN pv_nom_solicitante VARCHAR(100),
    IN pv_des_notas_res   TEXT,
    -- Asignación
    IN pi_cod_asignacion  BIGINT,
    IN pi_cod_evento_asig BIGINT,
    IN pi_can_asignada    INT,
    IN pdt_fec_salida     DATETIME,
    IN pdt_fec_retorno    DATETIME,
    IN pv_nom_resp_asig   VARCHAR(100),
    IN pv_des_observ      TEXT,
    -- Auditoría
    IN pv_usr_registro    VARCHAR(255)
)
BEGIN
    START TRANSACTION;

    -- Insertar ítem (obligatorio)
    IF pv_nom_item IS NOT NULL THEN
        INSERT INTO IN_INVENTARIO_ITEM
            (NOM_ITEM, DES_ITEM, COD_CATEGORIA, COD_ALMACEN, CAN_TOTAL, CAN_DISPONIBLE,
             COD_ITEM_UNICO, IMG_FOTO_URL, FEC_ADQUISICION, MON_COSTO, USR_REGISTRO, FEC_REGISTRO)
        VALUES
            (pv_nom_item, pv_des_item, pi_cod_categoria, pi_cod_almacen,
             IFNULL(pi_can_total, 0), IFNULL(pi_can_disponible, 0),
             pv_cod_item_unico, pv_img_foto_url, pd_fec_adquisicion, pm_mon_costo,
             IFNULL(pv_usr_registro, 'Sistema'), NOW());

        SET pi_cod_item = LAST_INSERT_ID();
    END IF;

    -- Insertar categoría (si viene nombre de categoría)
    IF pv_nom_categoria IS NOT NULL AND pi_cod_categoria IS NULL THEN
        INSERT INTO IN_CATEGORIAS_INVENTARIO
            (NOM_CATEGORIA, DES_CATEGORIA, DES_ICONO, IND_ACTIVA, USR_REGISTRO, FEC_REGISTRO)
        VALUES
            (pv_nom_categoria, pv_des_categoria, pv_des_icono, TRUE,
             IFNULL(pv_usr_registro, 'Sistema'), NOW());
    END IF;

    -- Insertar almacén (si viene nombre de almacén)
    IF pv_nom_almacen IS NOT NULL AND pi_cod_almacen IS NULL THEN
        INSERT INTO IN_ALMACENES
            (NOM_ALMACEN, DIR_UBICACION, COD_EMPLEADO, CAN_CAPACIDAD, IND_ACTIVO, USR_REGISTRO, FEC_REGISTRO)
        VALUES
            (pv_nom_almacen, pv_dir_ubicacion, pi_cod_empleado, pi_can_capacidad, TRUE,
             IFNULL(pv_usr_registro, 'Sistema'), NOW());
    END IF;

    -- Insertar reserva (si viene evento y cantidad reservada)
    IF pi_cod_evento_res IS NOT NULL AND pi_can_reservada IS NOT NULL AND pi_cod_item IS NOT NULL THEN
        INSERT INTO IN_RESERVAS_INVENTARIO
            (COD_EVENTO, COD_ITEM, CAN_RESERVADA, FEC_INICIO_RESERVA, FEC_FIN_RESERVA,
             IND_ESTADO_RESERVA, NOM_SOLICITANTE, DES_NOTAS, USR_REGISTRO, FEC_REGISTRO)
        VALUES
            (pi_cod_evento_res, pi_cod_item, pi_can_reservada,
             IFNULL(pdt_fec_inicio_res, NOW()), IFNULL(pdt_fec_fin_res, NOW()),
             'ACTIVA', pv_nom_solicitante, pv_des_notas_res,
             IFNULL(pv_usr_registro, 'Sistema'), NOW());

        -- Reducir disponible
        UPDATE IN_INVENTARIO_ITEM
        SET CAN_DISPONIBLE = CAN_DISPONIBLE - pi_can_reservada
        WHERE COD_ITEM = pi_cod_item;
    END IF;

    -- Insertar asignación (si viene evento y cantidad asignada)
    IF pi_cod_evento_asig IS NOT NULL AND pi_can_asignada IS NOT NULL AND pi_cod_item IS NOT NULL THEN
        INSERT INTO IN_ASIGNACION_EVENTO
            (COD_EVENTO, COD_ITEM, CAN_ASIGNADA, FEC_SALIDA, FEC_RETORNO,
             IND_ESTADO, NOM_RESPONSABLE, DES_OBSERVACIONES, USR_REGISTRO, FEC_REGISTRO)
        VALUES
            (pi_cod_evento_asig, pi_cod_item, pi_can_asignada,
             IFNULL(pdt_fec_salida, NOW()), pdt_fec_retorno,
             'PENDIENTE', pv_nom_resp_asig, pv_des_observ,
             IFNULL(pv_usr_registro, 'Sistema'), NOW());

        -- Reducir disponible
        UPDATE IN_INVENTARIO_ITEM
        SET CAN_DISPONIBLE = CAN_DISPONIBLE - pi_can_asignada
        WHERE COD_ITEM = pi_cod_item;
    END IF;

    COMMIT;
END$$
DELIMITER ;

-- ─────────────────────────────────────────────────────────────────────────────
-- 3. SP_IN_UPDATE  →  36 parámetros según inventarioModel.js
-- ─────────────────────────────────────────────────────────────────────────────
DROP PROCEDURE IF EXISTS SP_IN_UPDATE;

DELIMITER $$
CREATE PROCEDURE SP_IN_UPDATE(
    -- Control (acción para soft deletes)
    IN pv_accion          VARCHAR(20),
    -- Auditoría
    IN pv_usr_registro    VARCHAR(255),
    -- Ítem
    IN pi_cod_item        BIGINT,
    IN pv_nom_item        VARCHAR(150),
    IN pv_des_item        TEXT,
    IN pi_can_total       INT,
    IN pi_can_disponible  INT,
    IN pv_ind_estado      ENUM('ACTIVO','BAJA','MANTENIMIENTO'),
    IN pv_cod_item_unico  VARCHAR(50),
    IN pv_img_foto_url    VARCHAR(255),
    IN pd_fec_adquisicion DATE,
    IN pm_mon_costo       DECIMAL(10,2),
    -- Categoría
    IN pi_cod_categoria   BIGINT,
    IN pv_nom_categoria   VARCHAR(100),
    IN pv_des_categoria   TEXT,
    IN pv_des_icono       VARCHAR(50),
    -- Almacén
    IN pi_cod_almacen     BIGINT,
    IN pv_nom_almacen     VARCHAR(100),
    IN pv_dir_ubicacion   VARCHAR(200),
    IN pi_cod_empleado    BIGINT,
    IN pi_can_capacidad   INT,
    -- Reserva
    IN pi_cod_reserva     BIGINT,
    IN pi_can_reservada   INT,
    IN pdt_fec_inicio_res DATETIME,
    IN pdt_fec_fin_res    DATETIME,
    IN pv_ind_estado_res  ENUM('ACTIVA','CANCELADA','COMPLETADA'),
    IN pv_nom_solicitante VARCHAR(100),
    IN pv_des_notas_res   TEXT,
    -- Asignación
    IN pi_cod_asignacion  BIGINT,
    IN pi_can_asignada    INT,
    IN pdt_fec_salida     DATETIME,
    IN pdt_fec_retorno    DATETIME,
    IN pv_ind_estado_asig ENUM('PENDIENTE','ENTREGADO','RETORNADO','PERDIDO'),
    IN pv_ind_condicion   ENUM('BUENO','DANIADO','PERDIDO'),
    IN pv_nom_resp_asig   VARCHAR(100),
    IN pv_des_observ      TEXT
)
BEGIN
    START TRANSACTION;

    -- ── SOFT DELETE ÍTEM ────────────────────────────────────────────────────
    IF pv_accion = 'DEL_IN_ITEM' THEN
        UPDATE IN_INVENTARIO_ITEM
        SET IND_ESTADO   = 'BAJA',
            USR_REGISTRO = IFNULL(pv_usr_registro, USR_REGISTRO),
            FEC_REGISTRO = NOW()
        WHERE COD_ITEM = pi_cod_item;

    -- ── SOFT DELETE CATEGORÍA ───────────────────────────────────────────────
    ELSEIF pv_accion = 'DEL_IN_CATEGORIA' THEN
        UPDATE IN_CATEGORIAS_INVENTARIO
        SET IND_ACTIVA   = FALSE,
            USR_REGISTRO = IFNULL(pv_usr_registro, USR_REGISTRO),
            FEC_REGISTRO = NOW()
        WHERE COD_CATEGORIA = pi_cod_categoria;

    -- ── SOFT DELETE ALMACÉN ─────────────────────────────────────────────────
    ELSEIF pv_accion = 'DEL_IN_ALMACEN' THEN
        UPDATE IN_ALMACENES
        SET IND_ACTIVO   = FALSE,
            USR_REGISTRO = IFNULL(pv_usr_registro, USR_REGISTRO),
            FEC_REGISTRO = NOW()
        WHERE COD_ALMACEN = pi_cod_almacen;

    -- ── UPDATE ÍTEM ─────────────────────────────────────────────────────────
    ELSEIF pi_cod_item IS NOT NULL AND pv_accion IS NULL THEN
        UPDATE IN_INVENTARIO_ITEM
        SET NOM_ITEM        = IFNULL(pv_nom_item,        NOM_ITEM),
            DES_ITEM        = IFNULL(pv_des_item,        DES_ITEM),
            CAN_TOTAL       = IFNULL(pi_can_total,       CAN_TOTAL),
            CAN_DISPONIBLE  = IFNULL(pi_can_disponible,  CAN_DISPONIBLE),
            IND_ESTADO      = IFNULL(pv_ind_estado,      IND_ESTADO),
            COD_ITEM_UNICO  = IFNULL(pv_cod_item_unico,  COD_ITEM_UNICO),
            IMG_FOTO_URL    = IFNULL(pv_img_foto_url,    IMG_FOTO_URL),
            FEC_ADQUISICION = IFNULL(pd_fec_adquisicion, FEC_ADQUISICION),
            MON_COSTO       = IFNULL(pm_mon_costo,       MON_COSTO),
            USR_REGISTRO    = IFNULL(pv_usr_registro,    USR_REGISTRO),
            FEC_REGISTRO    = NOW()
        WHERE COD_ITEM = pi_cod_item;

    -- ── UPDATE CATEGORÍA ────────────────────────────────────────────────────
    ELSEIF pi_cod_categoria IS NOT NULL AND pv_accion IS NULL THEN
        UPDATE IN_CATEGORIAS_INVENTARIO
        SET NOM_CATEGORIA = IFNULL(pv_nom_categoria, NOM_CATEGORIA),
            DES_CATEGORIA = IFNULL(pv_des_categoria, DES_CATEGORIA),
            DES_ICONO     = IFNULL(pv_des_icono,     DES_ICONO),
            USR_REGISTRO  = IFNULL(pv_usr_registro,  USR_REGISTRO),
            FEC_REGISTRO  = NOW()
        WHERE COD_CATEGORIA = pi_cod_categoria;

    -- ── UPDATE ALMACÉN ──────────────────────────────────────────────────────
    ELSEIF pi_cod_almacen IS NOT NULL AND pv_accion IS NULL THEN
        UPDATE IN_ALMACENES
        SET NOM_ALMACEN   = IFNULL(pv_nom_almacen,   NOM_ALMACEN),
            DIR_UBICACION = IFNULL(pv_dir_ubicacion,  DIR_UBICACION),
            COD_EMPLEADO  = IFNULL(pi_cod_empleado,   COD_EMPLEADO),
            CAN_CAPACIDAD = IFNULL(pi_can_capacidad,  CAN_CAPACIDAD),
            USR_REGISTRO  = IFNULL(pv_usr_registro,   USR_REGISTRO),
            FEC_REGISTRO  = NOW()
        WHERE COD_ALMACEN = pi_cod_almacen;

    -- ── UPDATE RESERVA ──────────────────────────────────────────────────────
    ELSEIF pi_cod_reserva IS NOT NULL AND pv_accion IS NULL THEN
        -- Si se cancela, reponer la cantidad disponible del ítem
        IF pv_ind_estado_res = 'CANCELADA' THEN
            UPDATE IN_INVENTARIO_ITEM ii
            JOIN IN_RESERVAS_INVENTARIO r ON ii.COD_ITEM = r.COD_ITEM
            SET ii.CAN_DISPONIBLE = ii.CAN_DISPONIBLE + r.CAN_RESERVADA
            WHERE r.COD_RESERVA = pi_cod_reserva
            AND r.IND_ESTADO_RESERVA <> 'CANCELADA';
        END IF;

        UPDATE IN_RESERVAS_INVENTARIO
        SET CAN_RESERVADA      = IFNULL(pi_can_reservada,   CAN_RESERVADA),
            FEC_INICIO_RESERVA = IFNULL(pdt_fec_inicio_res, FEC_INICIO_RESERVA),
            FEC_FIN_RESERVA    = IFNULL(pdt_fec_fin_res,    FEC_FIN_RESERVA),
            IND_ESTADO_RESERVA = IFNULL(pv_ind_estado_res,  IND_ESTADO_RESERVA),
            NOM_SOLICITANTE    = IFNULL(pv_nom_solicitante,  NOM_SOLICITANTE),
            DES_NOTAS          = IFNULL(pv_des_notas_res,    DES_NOTAS),
            USR_REGISTRO       = IFNULL(pv_usr_registro,     USR_REGISTRO),
            FEC_REGISTRO       = NOW()
        WHERE COD_RESERVA = pi_cod_reserva;

    -- ── UPDATE ASIGNACIÓN ───────────────────────────────────────────────────
    ELSEIF pi_cod_asignacion IS NOT NULL AND pv_accion IS NULL THEN
        -- Si retorna o se pierde, reponer disponible
        IF pv_ind_estado_asig IN ('RETORNADO','PERDIDO') THEN
            UPDATE IN_INVENTARIO_ITEM ii
            JOIN IN_ASIGNACION_EVENTO ae ON ii.COD_ITEM = ae.COD_ITEM
            SET ii.CAN_DISPONIBLE = ii.CAN_DISPONIBLE + ae.CAN_ASIGNADA
            WHERE ae.COD_ASIGNACION = pi_cod_asignacion
            AND ae.IND_ESTADO NOT IN ('RETORNADO','PERDIDO');
        END IF;

        UPDATE IN_ASIGNACION_EVENTO
        SET CAN_ASIGNADA      = IFNULL(pi_can_asignada,    CAN_ASIGNADA),
            FEC_SALIDA        = IFNULL(pdt_fec_salida,     FEC_SALIDA),
            FEC_RETORNO       = IFNULL(pdt_fec_retorno,    FEC_RETORNO),
            IND_ESTADO        = IFNULL(pv_ind_estado_asig, IND_ESTADO),
            IND_CONDICION     = IFNULL(pv_ind_condicion,   IND_CONDICION),
            NOM_RESPONSABLE   = IFNULL(pv_nom_resp_asig,   NOM_RESPONSABLE),
            DES_OBSERVACIONES = IFNULL(pv_des_observ,      DES_OBSERVACIONES),
            USR_REGISTRO      = IFNULL(pv_usr_registro,    USR_REGISTRO),
            FEC_REGISTRO      = NOW()
        WHERE COD_ASIGNACION = pi_cod_asignacion;

    END IF;

    COMMIT;
END$$
DELIMITER ;
