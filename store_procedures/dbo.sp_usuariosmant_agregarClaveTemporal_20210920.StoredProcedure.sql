USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_usuariosmant_agregarClaveTemporal_20210920]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO

-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 20-06-2019
-- Descripcion:	Agregar clave temporal del usuario
-- Ejemplo:exec sp_usuariosmant_agregarClaveTemporal '26131316-2','123456'
-- =============================================
CREATE PROCEDURE [dbo].[sp_usuariosmant_agregarClaveTemporal_20210920]
	@pusuarioid    NVARCHAR(50), -- id del usuario
	@pclave        NVARCHAR(100) -- clave
AS          
BEGIN
	SET NOCOUNT ON;

	DECLARE @error        INT
	DECLARE @mensaje      VARCHAR(100)
	
	UPDATE usuarios SET claveTemporal = @pclave WHERE usuarioid = @pusuarioid     

	SELECT @error AS error, @mensaje AS mensaje;
END
GO
