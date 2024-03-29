USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_confImpResultado_eliminar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 08/08/2018
-- Descripcion: Eliminar resultado importacion
-- Ejemplo:exec sp_confImp_eliminar '9798215-5'
-- =============================================
CREATE PROCEDURE [dbo].[sp_confImpResultado_eliminar]
	@usuarioid nvarchar(10),
    @IdArchivo  		INT
AS
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
	
								
	IF EXISTS (SELECT usuarioid FROM ConfImpResultado WHERE usuarioid = @usuarioid)
		BEGIN
			DELETE 
			FROM ConfImpResultado
			WHERE usuarioid = @usuarioid
            AND IdArchivo = @IdArchivo
			
		END 
		
	SELECT @error= 0
	SELECT @mensaje = ''	
	
	SELECT @error AS error, @mensaje AS mensaje;
  
END
GO
