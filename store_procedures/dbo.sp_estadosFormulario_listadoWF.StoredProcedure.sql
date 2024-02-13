USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_estadosFormulario_listadoWF]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernanddez
-- Creado el: 04-02-2020
-- Descripcion: Listar los estado de gestion
-- Modificado: gdiaz 13/04/2021
-- Ejemplo:  sp_estadosFormulario_listadoWF
-- =============================================
CREATE PROCEDURE [dbo].[sp_estadosFormulario_listadoWF]

AS
BEGIN
    SELECT
        ISNULL(ContratosEstados.Descripcion, 'Documento no generado') AS estadoFujoFirma,
        ISNULL(ContratosEstados.idEstado, '-1') AS idEstadoFujoFirma
    FROM empleadoFormulario
    INNER JOIN formularioPlantilla ON formularioPlantilla.idFormulario = empleadoFormulario.idFormulario
    INNER JOIN estadoFormulario ON estadoFormulario.estadoFormularioid = empleadoFormulario.estadoFormularioid
    LEFT JOIN Contratos ON Contratos.idDocumento = empleadoFormulario.idDocumento
    LEFT JOIN ContratosEstados ON ContratosEstados.idEstado = Contratos.idEstado
    WHERE empleadoFormulario.estadoFormularioid IN (1,8) -- Asignado - Cancelado
    GROUP BY ContratosEstados.Descripcion, ContratosEstados.idEstado
END
GO
