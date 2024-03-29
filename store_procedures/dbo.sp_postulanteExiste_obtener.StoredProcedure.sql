USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_postulanteExiste_obtener]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/14/2018
-- Descripcion: Obtiene el nombre de persona
-- Ejemplo:exec sp_postulanteExiste_obtener
-- =============================================
CREATE PROCEDURE [dbo].[sp_postulanteExiste_obtener]
    @personaid VARCHAR (10),
    @RutEmpresa VARCHAR (10),
    @centrocostoid VARCHAR (14),
    @idCargoEmpleado VARCHAR (14),
    @fechaPostulacionMIN DATE
AS
BEGIN
	
    SELECT
        Postulaciones.fechaPostulacion
	FROM Postulaciones 
    INNER JOIN Postulantes ON Postulantes.postulanteid = Postulaciones.postulanteid
    WHERE
        Postulantes.rut = @personaid AND
        Postulaciones.RutEmpresa = @RutEmpresa AND
        --Postulaciones.centrocostoid = @centrocostoid AND
        Postulaciones.idCargoEmpleado = @idCargoEmpleado AND
        Postulaciones.fechaPostulacion >= @fechaPostulacionMIN
    RETURN                                                             

END
GO
