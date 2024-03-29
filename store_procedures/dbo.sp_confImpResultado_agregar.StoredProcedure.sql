USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_confImpResultado_agregar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 08/08/2018
-- Descripcion: Graba resultado de importacion
-- Ejemplo:exec sp_confImpResultado_agregar '11111111-1',1,'OK','','nuevo registro'
-- =============================================
CREATE PROCEDURE [dbo].[sp_confImpResultado_agregar]
	@usuarioid			NVARCHAR (10),
	@fila				INT,
	@resultado			NVARCHAR (10),
	@observaciones		NVARCHAR (500),
	@tipotransaccion	NVARCHAR (20),
    @IdArchivo  		INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT;
	
	
	INSERT INTO ConfimpResultado (usuarioid,fila,resultado,observaciones,tipotransaccion,IdArchivo)
	VALUES (@usuarioid,@fila,@resultado,@observaciones,@tipotransaccion,@IdArchivo)
			
	SELECT @lmensaje = ''
	SELECT @error = 0
	
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
