USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_enviocorreos_agregar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Haydelis Hernandez	
-- Creado el: 04/10/2018
-- Descripcion: Agregar una notificacion de correo 
-- Ejemplo:exec sp_enviocorreos_agregar 
-- =============================================
CREATE PROCEDURE [dbo].[sp_enviocorreos_agregar]
	@documentoid		INT, --Documento del que se va enviar
	@estado				INT  --Estado que se va a ocupar 
AS	
BEGIN
	SET NOCOUNT ON;
	DECLARE @codcorreo INT; 
	
	IF EXISTS ( SELECT C.CodCorreo FROM CorreosEstados EC INNER JOIN Correo C ON C.CodCorreo = EC.estadoid WHERE EC.estadoid = @estado AND EC.consulta = 1 ) 
		BEGIN 
		
			SELECT @codcorreo = C.CodCorreo FROM CorreosEstados EC INNER JOIN Correo C ON C.CodCorreo = EC.estadoid WHERE EC.estadoid = @estado
		
			INSERT INTO EnvioCorreos( documentoid, EnviaCorreo,FechaEnvio, CodCorreo)
							  VALUES( @documentoid, 0, NULL, @codcorreo )
		END 
	
	RETURN;
END
GO
