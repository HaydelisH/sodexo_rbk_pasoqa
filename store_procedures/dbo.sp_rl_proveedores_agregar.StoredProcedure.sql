USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_proveedores_agregar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 13/06/2018
-- Descripcion: Agregar clientes
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec sp_rl_proveedores_agregar 'agregar','11933870-0','Nombre Razon Social','Direccion Cliente','Comuna Cliente','Ciudad Cliente',2
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_proveedores_agregar]
	@pAccion CHAR (60),
	@RutProveedor VARCHAR (10),
	@NombreProveedor VARCHAR (50),
	@Direccion VARCHAR (50), 
	@Comuna VARCHAR (50),
	@Ciudad VARCHAR (50),
	@RutEmpresa VARCHAR(10)
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
		IF NOT EXISTS (SELECT RutProveedor FROM rl_Proveedores WHERE RutProveedor = @RutProveedor )
			BEGIN				
				INSERT INTO rl_Proveedores
				(
				RutProveedor,
				--RutEmpresa,
				NombreProveedor, 
				Direccion, 
				Comuna, 
				Ciudad,
				Eliminado
				)
				VALUES(
				@RutProveedor, 
				--@RutEmpresa,
				@NombreProveedor, 
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
				--SELECT @eliminado = Eliminado FROM clientes WHERE RutProveedor = @RutProveedor 
				--IF @eliminado = 0
				--   BEGIN
				--		SELECT @lmensaje = 'ESTA Cliente YA EXISTE'
				--		SELECT @error = 1
				--   END 
				--ELSE 
				--	BEGIN 
						UPDATE rl_Proveedores SET
						Eliminado = 0,
						RutProveedor= @RutProveedor,
					--	RutEmpresa = @RutEmpresa,
				        NombreProveedor= @NombreProveedor,
				        Direccion= @Direccion,
						Comuna= @Comuna, 
						Ciudad= @Ciudad
						WHERE RutProveedor = @RutProveedor --AND RutEmpresa = @RutEmpresa
				 
					    SELECT @lmensaje = ''
				        SELECT @error = 0
				--	END   
			END 
	  END		
    SELECT @error AS error, @lmensaje AS mensaje 
END

GO
