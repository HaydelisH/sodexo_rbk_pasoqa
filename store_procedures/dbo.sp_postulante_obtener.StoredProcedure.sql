USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_postulante_obtener]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/14/2018
-- Descripcion: Obtiene el nombre de persona
-- Ejemplo:exec sp_postulante_obtener
-- =============================================
CREATE PROCEDURE [dbo].[sp_postulante_obtener]
	@rutPostulante VARCHAR (10)
AS
BEGIN
	
    SELECT
        Postulantes.postulanteid,
		Postulantes.rut, 
		Postulantes.nombre, 
		Postulantes.telefono, 
		Postulantes.email,
        Postulantes.Observacion,
        Postulantes.discapacidad
	FROM Postulantes 
    INNER JOIN EstadosPostulante ON EstadosPostulante.estadoPostulanteid = Postulantes.estadoPostulanteid
    WHERE rut = @rutPostulante
    RETURN                                                             

END
GO
