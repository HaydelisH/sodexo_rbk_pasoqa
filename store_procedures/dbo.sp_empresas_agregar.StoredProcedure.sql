USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empresas_agregar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 13/06/2018
-- Descripcion: Agregar Empresas
-- Ejemplo:exec sp_empresas_agregar 'agregar','11933870-0','Nombre Razon Social','Direccion Empresa','Comuna Empresa','Ciudad Empresa',2
-- =============================================
CREATE PROCEDURE [dbo].[sp_empresas_agregar]
	@pAccion CHAR (60),
	@RutEmpresa VARCHAR (10),
	@RazonSocial VARCHAR (50),
	@Direccion VARCHAR (50), 
	@Comuna VARCHAR (50),
	@Ciudad VARCHAR (50)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
	DECLARE @eliminado  BIT;
			
    -- Insert statements for procedure here
    IF (@pAccion='agregar')  
    BEGIN
		IF NOT EXISTS (SELECT RutEmpresa FROM Empresas WHERE RutEmpresa = @RutEmpresa AND Eliminado = 0)
			BEGIN				
				INSERT INTO Empresas
				(
				RutEmpresa,
				RazonSocial, 
				Direccion, 
				Comuna, 
				Ciudad,
				Eliminado
				)
				VALUES(
				@RutEmpresa, 
				@RazonSocial, 
				@Direccion, 
				@Comuna, 
				@Ciudad,
				0
				)
				
				
				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
		ELSE
			BEGIN
				--SELECT @eliminado = Eliminado FROM Empresas WHERE RutEmpresa = @RutEmpresa 
				--IF @eliminado = 0
				--   BEGIN
				--		SELECT @lmensaje = 'ESTA EMPRESA YA EXISTE'
				--		SELECT @error = 1
				--   END 
				--ELSE 
				--	BEGIN 
						UPDATE Empresas 
						SET Eliminado = 0,
						RutEmpresa= @RutEmpresa,
				        RazonSocial= @RazonSocial,
				        Direccion= @Direccion,
						Comuna= @Comuna, 
						Ciudad= @Ciudad
						WHERE RutEmpresa = @RutEmpresa
				 
					    SELECT @lmensaje = ''
				        SELECT @error = 0
					--END   
			END 
	  END		
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
