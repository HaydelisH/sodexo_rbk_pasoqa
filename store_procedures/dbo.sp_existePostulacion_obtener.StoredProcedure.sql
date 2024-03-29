USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_existePostulacion_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/14/2018
-- Descripcion: Obtiene el nombre de persona
-- Ejemplo:exec sp_existePostulacion_obtener
-- =============================================
CREATE PROCEDURE [dbo].[sp_existePostulacion_obtener]
    @rut VARCHAR(10),
    @RutEmpresa VARCHAR(10),
    @idCargoEmpleado VARCHAR(14)
AS
BEGIN
	
    SELECT
        Postulaciones.postulacionid,
        Postulantes.postulanteid,
        Postulantes.blackList
    FROM Postulantes 
    INNER JOIN Postulaciones 
    ON Postulaciones.postulanteid = Postulantes.postulanteid 
    WHERE Postulantes.rut = @rut 
        AND Postulaciones.idCargoEmpleado = @idCargoEmpleado 
        AND Postulaciones.RutEmpresa = @RutEmpresa
    ORDER BY Postulaciones.fechaPostulacion DESC
    RETURN                                                             

END
GO
