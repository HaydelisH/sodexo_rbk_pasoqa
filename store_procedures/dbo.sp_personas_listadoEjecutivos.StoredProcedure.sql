USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_personas_listadoEjecutivos]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/14/2018
-- Descripcion: Obtiene el listado de todos los Ejecutivos 
-- Ejemplo:exec sp_personas_listadoEjecutivos 
-- =============================================
create PROCEDURE [dbo].[sp_personas_listadoEjecutivos]
AS
BEGIN
    SELECT 
    personas.personaid,
    personas.nombre,
    personas.apmaterno
    FROM personas
	INNER JOIN 
	SupervisorEjecutivo
	ON
	personas.personaid = SupervisorEjecutivo.RutEjecutivo
	WHERE
    personas.Eliminado = 0                     
    RETURN                                                             

END
GO
