USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_parametros_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_parametros_obtener]
	@pparametro VARCHAR(50)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
   
    BEGIN
		SELECT 
		idparametro,
		parametro,
		descripcion
		FROM Parametros
		WHERE idparametro = @pparametro 
				
	END
END
GO
