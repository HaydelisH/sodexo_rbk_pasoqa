USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_clausulas_aprobar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/01/2018
-- Descripcion: Aprueba una Clausula
-- Ejemplo:exec sp_clausulas_aprobar 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_clausulas_aprobar]
	@idClausula INT,
	@RutAprobador VARCHAR(10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		BIT;
	
	BEGIN 		
		IF EXISTS (SELECT idClausula FROM Clausulas WHERE idClausula = @idClausula AND Eliminado=0)
			BEGIN
			IF EXISTS (SELECT idClausula FROM Clausulas WHERE idClausula = @idClausula AND Aprobado=0)
				BEGIN
					UPDATE Clausulas SET  Aprobado = 1, RutAprobador = @RutAprobador WHERE idClausula = @idClausula 
					SELECT @lmensaje = ''
					SELECT @error = 0
				END	
			ELSE
				BEGIN
					SELECT @lmensaje = 'ESTA CATEGORIA YA ESTA APROBADA'
					SELECT @error = 1
				END
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
