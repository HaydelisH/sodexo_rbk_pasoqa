USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empresas_eliminar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 13/06/2018
-- Descripcion: Eliminar Empresas
-- Ejemplo:exec sp_empresas_eliminar 'eliminar','11933870-0'
-- =============================================
CREATE PROCEDURE [dbo].[sp_empresas_eliminar]
	@pAccion CHAR(60),
	@RutEmpresa VARCHAR (10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
	IF (@pAccion='eliminar') 
    BEGIN
		IF EXISTS (SELECT RutEmpresa FROM Empresas WHERE RutEmpresa = @RutEmpresa AND Eliminado=0 )
			BEGIN
				UPDATE Empresas SET Eliminado = 1
				WHERE RutEmpresa = @RutEmpresa 
				
				DELETE FROM Firmantes WHERE RutEmpresa = @RutEmpresa
				
				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
		ELSE
			BEGIN
				SELECT @lmensaje = 'ESTA EMPRESA NO EXISTE'
				SELECT @error = 1
			END 
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
