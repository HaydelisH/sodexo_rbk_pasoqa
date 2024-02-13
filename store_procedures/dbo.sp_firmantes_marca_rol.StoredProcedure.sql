USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_firmantes_marca_rol]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 20/08/2018
-- Descripcion: Marca usuario de que ya fue creado el rol 
-- Ejemplo:exec sp_firmantes_marca_rol '11111111-1','55555555-5'
-- =============================================
create  PROCEDURE [dbo].[sp_firmantes_marca_rol]
@pfirmanteid		NVARCHAR(10),	-- rut firmamte
@prutempresa		NVARCHAR(10)	-- rut empresa
AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)


	UPDATE Firmantes SET tienerol = 1
	WHERE RutUsuario = @pfirmanteid
	AND RutEmpresa = @prutempresa
	
	SELECT @mensaje = ''
	SELECT @error = 0

				
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
