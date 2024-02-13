USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tipomovimiento_obtener]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_tipomovimiento_obtener]
AS
BEGIN
	SET NOCOUNT ON;
   
	BEGIN
        SELECT 
            TipoMovimiento.idTipoMovimiento, 
            TipoMovimiento.Descripcion
        FROM TipoMovimiento;
    END;
END;
GO
