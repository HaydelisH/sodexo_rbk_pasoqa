USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_personas_agregar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 28/08/2018
-- Descripcion: Agregar datos de personas
-- Ejemplo:exec sp_personas_agregar
-- =============================================
CREATE PROCEDURE [dbo].[sp_personas_agregar]
	@personaid VARCHAR (10),
	@nombre VARCHAR(110),
	@appaterno VARCHAR(50),
	@correo VARCHAR(60)
AS
BEGIN
	
    IF NOT EXISTS (SELECT personaid FROM personas WHERE personaid = @personaid)
		BEGIN 
			INSERT INTO personas (personaid, nacionalidad, nombre, appaterno, apmaterno, correo, Eliminado ) VALUES( @personaid, '', @nombre, @appaterno,'', @correo, 0)
		END                 
END
GO
