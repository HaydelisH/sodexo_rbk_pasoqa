USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_clausulas_modificar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/01/2018
-- Descripcion: Actualiza los datos de una Clausula
-- Ejemplo:exec sp_clausulas_modificar 'modificar',1,'','Titulo','Descripcion','Texto',1,'xxx','xxx','xxx'
-- =============================================
CREATE PROCEDURE [dbo].[sp_clausulas_modificar]
	@pAccion CHAR(60),
	@idClausula INT,
	@Titulo_Cl VARCHAR (MAX),
	@Descripcion_Cl VARCHAR (MAX), 
	@Texto VARCHAR(MAX),
	@idCategoria INT,
	@RutModificador VARCHAR (10),
	@RutAprobador VARCHAR (10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
	IF (@pAccion='modificar') 
    BEGIN
		IF EXISTS (SELECT idClausula FROM Clausulas WHERE idClausula = @idClausula AND Eliminado=0)
			BEGIN
				UPDATE Clausulas SET 
				Titulo_Cl = @Titulo_Cl,
				Descripcion_Cl = @Descripcion_Cl, 
				Texto = @Texto,
				idCategoria = @idCategoria,
				RutModificador = @RutModificador, 
				RutAprobador = @RutAprobador, 
				Aprobado = 0 
				WHERE idClausula = @idClausula 
				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
		ELSE
			BEGIN
				SELECT @lmensaje = 'ESTA CATEGORIA NO EXISTE'
				SELECT @error = 1
			END 
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
